<?php
namespace controlador;

use modelo\entidades\Menu;
use fachada\Fachada;
use lib\DataTables\DataTables;
use modelo\entidades\Permissao;
use lib\DataTables\BotaoDataTable;
use modelo\entidades\Produto;
use lib\JSONResponse;
use lib\util\Util;

class CadastroProduto extends Modulo {
	
	const DIRETORIO_VISAO = "produto/index";
	const MSG_OPERACAO_SUCESSO = "Operação realizada com sucesso!";
	const MODULO = 'CLIENTE';
	
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
			
			$this->idmenu = Menu::PRODUTOS;
			
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
			
			$nome = trim($_POST['nome']);
			$valor = trim(Util::formartarNumeroDecimal($_POST['valor']));
			$descricao = trim($_POST['descricao']);
			
			// Valida NOME
			if (empty($nome)){
				throw new \InvalidArgumentException("Favor preencher o nome do produto");
			}
			
			// Valida VALOR
			if (empty($valor)){
				throw new \InvalidArgumentException("Favor preencher o valor do produto");
			}
			
			$produto = new Produto();
			$produto->id = $_POST['id'];
			$produto->nome = $nome;
			$produto->valor = $valor;
			$produto->descricao = $descricao;
			
			if (empty($produto->id)){
				$this->fachada->inserirProduto($produto);
			} else {
				$this->fachada->atualizarProduto($produto);
			}
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		}catch (\Exception $ex){
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function listar() {
		try {
			
			$produtos = $this->fachada->listarProdutos();
			foreach ($produtos->toArray() as $produto){
				$this->dataTables->addRow(array($produto->id, $produto->nome, $produto->valor));
			}
			
			echo $this->dataTables;
			
		} catch (Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function buscarProduto() {
		try {
			
			if (empty($_POST['id'])){
				throw new \InvalidArgumentException("Favor selecionar um produto");
			}
				
			$produto = new Produto();
			$produto->id = $_POST['id'];
				
			$produto = $this->fachada->buscarProduto($produto);
			
			echo new JSONResponse(true, $produto);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function excluirProduto() {
		try {
			
			if (empty($_POST['id'])) {
				throw new \Exception("Selecione um produto.");
			}
				
			$produto = new Produto();
			$produto->id = $_POST['id'];
				
			$this->fachada->excluirProduto($produto);
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	private function setarBotoes(){
		if ($this->validarPermissao(Permissao::VISUALIZAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Visualizar', 'buscarProduto', 'clip-zoom-in', 'visualizar'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Editar', 'buscarProduto', 'icon-edit', 'editar'));
		}
		if ($this->validarPermissao(Permissao::REMOVER)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Excluir', 'excluirProduto', 'icon-remove icon-white', 'excluir'));
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
