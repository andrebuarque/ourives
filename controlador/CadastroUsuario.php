<?php
namespace controlador;

use fachada\Fachada;
use lib\DataTables\DataTables;
use lib\DataTables\BotaoDataTable;
use lib\JSONResponse;
use modelo\entidades\Usuario;
use modelo\entidades\Perfil;
use modelo\entidades\Menu;
use modelo\entidades\Permissao;

class CadastroUsuario extends Modulo {
	
	const DIRETORIO_VISAO = "usuario/index";
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
		
		try{
			
			$this->idmenu = Menu::USUARIOS;
			
			$this->usuarioLogado = $this->getUsuarioLogado();
			
			$this->fachada = new Fachada();
			$this->dataTables = new DataTables();
			$this->cadastro = false;
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
			
			$perfis = $this->fachada->listarPerfisAtivos();
			Facil::setar("perfisUsuario", $perfis->toArray());
			
			$this->templatePlugin->carregarLayoutCompleto(self::DIRETORIO_VISAO);
			
		}catch (ControleException $ex){
			Facil::despacharErro(404, "Página não encontrada");
		}
	}
	
	public function cadastrar(){
		try {
			
			if (empty($_POST['nome'])){
				throw new \InvalidArgumentException("Favor preencher o nome do usuário");
			}
			
			if (empty($_POST['perfil'])){
				throw new \InvalidArgumentException("Favor selecionar o perfil do usuárui");
			}
			
			if (empty($_POST['email'])){
				throw new \InvalidArgumentException("Favor preencher o e-mail do usuário");
			}
			
			$perfil = new Perfil();
			$perfil->id = $_POST['perfil'];
			$perfil = $this->fachada->buscarPerfil($perfil);
			
			$usuario = new Usuario();
			$usuario->id = $_POST['id'];
			$usuario->nome = $_POST['nome'];
			$usuario->email = $_POST['email'];
			$usuario->ativo = !empty($_POST['ativo']);
			$usuario->perfil = $perfil;
			
			if (empty($usuario->id)){
				$this->fachada->cadastrarUsuario($usuario);
			} else {
				$this->fachada->alterarUsuario($usuario);
			}
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		}catch (\Exception $ex){
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function listarUsuarios() {
		try {
			
			$usuarios = $this->fachada->listarUsuarios();
			foreach ($usuarios->toArray() as $usuario){
				$ativo = $usuario->ativo ? Usuario::ATIVO : Usuario::INATIVO;
				$this->dataTables->addRow(array($usuario->id, $usuario->nome, $usuario->email,
						$usuario->perfil->titulo, $ativo));
			}
			
			echo $this->dataTables;
			
		} catch (Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function buscarUsuario() {
		try {
			
			if (empty($_POST['id'])){
				throw new \InvalidArgumentException("Favor selecionar um usuário");
			}
			
			$usuario = new Usuario();
			$usuario->id = $_POST['id'];
			
			$usuario = $this->fachada->buscarUsuario($usuario);
			
			echo new JSONResponse(true, $usuario);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function excluirUsuario() {
		try {
			
			if (empty($_POST['id'])) {
				throw new \Exception("Selecione um usuário.");
			}
			
			$usuario = new Usuario();
			$usuario->id = $_POST['id'];
			
			$this->fachada->excluirUsuario($usuario);
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	private function setarBotoes(){
		if ($this->validarPermissao(Permissao::VISUALIZAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Visualizar', 'buscarUsuario', 'clip-zoom-in', 'visualizar'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Editar', 'buscarUsuario', 'icon-edit', 'editar'));
		}
		if ($this->validarPermissao(Permissao::REMOVER)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Excluir', 'excluirUsuario', 'icon-remove icon-white', 'excluir'));
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
