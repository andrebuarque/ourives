<?php
namespace controlador;

use fachada\Fachada;

use lib\DataTables\DataTables;
use lib\DataTables\BotaoDataTable;
use lib\JSONResponse;
use modelo\entidades\Perfil;
use modelo\entidades\Menu;
use modelo\entidades\Permissao;
use modelo\entidades\Usuario;
use Doctrine\Common\Collections\ArrayCollection;

class CadastroPerfil extends Modulo {
	
	const DIRETORIO_VISAO = "perfil/index";
	const MSG_OPERACAO_SUCESSO = "Operação realizada com sucesso!";
	
	/**
	 * @var DataTables
	 */
	private $dataTables;
	
	/**
	 * @var Fachada
	 */
	private $fachada;
	
	/**
	 * @var Usuario
	 */
	private $usuarioLogado;
	
	private $idmenu;
	
	private $cadastro;
	
	/**
	 * @var Menu
	 */
	private $menu;
	
	public function __construct() {
		parent::__construct();
		
		try {
			
			$this->idmenu = Menu::PERFIL;
			
			$this->usuarioLogado = $this->getUsuarioLogado();
			$this->cadastro = false;
			$this->fachada = new Fachada();
			$this->dataTables = new DataTables();
			$this->setarBotoes();
			
			$menu = new Menu();
			$menu->id = $this->idmenu;
			$this->menu = $this->fachada->buscarMenu($menu);
			
			Facil::setar("modulo", $this->menu);
			
		}catch (\Exception $ex){
			Facil::despacharErro(500, "Aplicação está indisponível no momento");
		}
	}
	
	public function index() {
		try {
	
			$this->listarMenus();
			$this->templatePlugin->carregarLayoutCompleto(self::DIRETORIO_VISAO);
			
		}catch (ControleException $ex){
			Facil::despacharErro(404, "Página não encontrada");
		}
	}
	
	public function inserirPerfil(){
		try{
		
			$id = trim($_POST['id']);
			$titulo = trim($_POST['titulo']);
			$ativo = !empty($_POST['ativo']);
			
			$permissoesVisualizar = !empty($_POST['visualizar']) ? array_keys($_POST['visualizar']) : array();
			$permissoesGravar = !empty($_POST['gravar']) ? array_keys($_POST['gravar']) : array();
			$permissoesExcluir = !empty($_POST['excluir']) ? array_keys($_POST['excluir']) : array();
			
			$perfil = new Perfil();
			$perfil->id = $id;
			if (!empty($perfil->id)) {
				$perfil = $this->fachada->buscarPerfil($perfil);
				$perfil->permissoes->clear();
			}
			$perfil->titulo = $titulo;
			$perfil->ativo = $ativo;
			
			$menusSelecionados = array_unique(
				array_merge(
					$permissoesVisualizar, 
					$permissoesGravar,
					$permissoesExcluir
				)
			);
			
			foreach ($menusSelecionados as $idmenu) {
				$menu = new Menu();
				$menu->id = $idmenu;
				$menu = $this->fachada->buscarMenu($menu);
				
				$permissao = new Permissao();
				$permissao->menu = $menu;
				$permissao->perfil = $perfil;
				$permissao->visualizar = in_array($menu->id, $permissoesVisualizar);
				$permissao->gravar = in_array($menu->id, $permissoesGravar);
				$permissao->remover = in_array($menu->id, $permissoesExcluir);
			
				$perfil->permissoes->add($permissao);
			}
			
			if (empty($perfil->id)) {
				$this->fachada->inserirPerfil($perfil);
			} else {
				$this->fachada->atualizarPerfil($perfil);
			}
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
		
		} catch (\Exception $ex) {
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function listarPerfis() {
		try{
			
			$perfis = $this->fachada->listar();
			foreach ($perfis as $perfil) {
				$ativo = $perfil->ativo ? Perfil::ATIVO : Perfil::INATIVO;
				$this->dataTables->addRow(array($perfil->id, $perfil->titulo, $ativo));
			}
			
			echo $this->dataTables;
		
		}catch (\Exception $ex){
			Facil::despacharErro(500, $ex->getMessage());
		}
	}
	
	public function buscarPerfil() {
		try {
			
			$id = array_key_exists('id', $_POST) ? $_POST['id'] : null;
			if (empty($id)) {
				throw new \Exception("Selecione um perfil.");
			}
			
			$perfil = new Perfil();
			$perfil->id = $id;
			$perfil = $this->fachada->buscarPerfil($perfil);
			$perfil->permissoes = $perfil->permissoes->toArray();
			
			echo new JSONResponse(true, $perfil);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function excluirPerfil() {
		try {
			
			$id = array_key_exists('id', $_POST) ? $_POST['id'] : null;
			if (empty($id)) {
				throw new \Exception("Selecione um perfil.");
			}
			
			$perfil = new Perfil();
			$perfil->id = $id;
			
			$this->fachada->excluirPerfil($perfil);
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	private function setarBotoes(){
		if ($this->validarPermissao(Permissao::VISUALIZAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Visualizar', 'buscarPerfil', 'clip-zoom-in', 'visualizar'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Editar', 'buscarPerfil', 'icon-edit', 'editar'));
		}
		if ($this->validarPermissao(Permissao::REMOVER)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Excluir', 'excluirPerfil', 'icon-remove icon-white', 'excluir'));
		}
	}
	 
	private function validarPermissao($acao) {
		$perfilUsuario = $this->usuarioLogado->perfil;
		$permissoes = $this->fachada->getPermissoesPerfil($perfilUsuario);
		foreach ($permissoes as $permissao) {
			if ($this->idmenu == $permissao['id']) {
				return $permissao[$acao];
				break;
			}
		}
		return false;
	}
	
	private function listarMenus() {
		$perfilUsuarioLogado = $this->usuarioLogado->perfil;
		$permissoes = $this->fachada->getPermissoesPerfil($perfilUsuarioLogado);
		$menus = $this->fachada->listarMenu();
		$this->cadastro = $this->validarPermissao(Permissao::GRAVAR);
		
		$arrIdMenusUsuario = array();
		foreach ($permissoes as $permissao) {
			$arrIdMenusUsuario[] = $permissao['id'];
		}
		Facil::setar("permissoes", $arrIdMenusUsuario);
		Facil::setar("menus", $menus);
		Facil::setar("cadastro", $this->cadastro);
	}
}
