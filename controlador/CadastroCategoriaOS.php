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
use modelo\entidades\CategoriaOS;

class CadastroCategoriaOS extends Modulo {
	
	const DIRETORIO_VISAO = "categoriaos/index";
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
			
			$this->idmenu = Menu::CATEGORIA_OS;
			
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
	
	public function inserir(){
		try{
		
			$id = trim($_POST['id']);
			$titulo = trim($_POST['titulo']);
			$ativo = !empty($_POST['ativo']);
			
			$categoria = new CategoriaOS();
			$categoria->id = $id;
			$categoria->titulo = $titulo;
			$categoria->ativo = $ativo;
			
			if (empty($categoria->id)) {
				$this->fachada->inserirCategoriasOS($categoria);
			} else {
				$this->fachada->atualizarCategoriasOS($categoria);
			}
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
		
		} catch (\Exception $ex) {
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function listar() {
		try{
			
			$categorias = $this->fachada->listarTodasCategoriasOS();
			foreach ($categorias as $categoria) {
				$ativo = $categoria->ativo ? CategoriaOS::ATIVO : CategoriaOS::INATIVO;
				$this->dataTables->addRow(array($categoria->id, $categoria->titulo, $ativo));
			}
			
			echo $this->dataTables;
		
		}catch (\Exception $ex){
			Facil::despacharErro(500, $ex->getMessage());
		}
	}
	
	public function buscar() {
		try {
			
			$id = array_key_exists('id', $_POST) ? $_POST['id'] : null;
			if (empty($id)) {
				throw new \Exception("Selecione uma categoria.");
			}
			
			$categoria = new CategoriaOS();
			$categoria->id = $id;
			$categoria = $this->fachada->buscarCategoriaOS($categoria);
			
			echo new JSONResponse(true, $categoria);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function excluir() {
		try {
			
			$id = array_key_exists('id', $_POST) ? $_POST['id'] : null;
			if (empty($id)) {
				throw new \Exception("Selecione uma categoria.");
			}
			
			$categoria = new CategoriaOS();
			$categoria->id = $id;
			
			$this->fachada->excluirCategoriaOS($categoria);
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	private function setarBotoes(){
		if ($this->validarPermissao(Permissao::VISUALIZAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Visualizar', 'buscar', 'clip-zoom-in', 'visualizar'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Editar', 'buscar', 'icon-edit', 'editar'));
		}
		if ($this->validarPermissao(Permissao::REMOVER)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Excluir', 'excluir', 'icon-remove icon-white', 'excluir'));
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
