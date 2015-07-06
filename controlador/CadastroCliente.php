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
use modelo\entidades\Cliente;
use lib\util\Validacao;
use lib\Log;
use modelo\entidades\Endereco;
use lib\util\Correios;

class CadastroCliente extends Modulo {
	
	const DIRETORIO_VISAO = "cliente/index";
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
			
			$this->idmenu = Menu::CLIENTES;
			
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
			$cpf = trim($_POST['cpf']);
			$email = trim($_POST['email']);
			
			// Valida NOME
			if (empty($nome)){
				throw new \InvalidArgumentException("Favor preencher o nome do cliente");
			}
			
			// Valida CPF
			if (empty($cpf)){
				throw new \InvalidArgumentException("Favor selecionar o CPF do cliente");
			} else if (!Validacao::isCPF($cpf)) {
				throw new \InvalidArgumentException("CPF inválido");
			}
			
			// Valida EMAIL
			if (!empty($email) && !Validacao::isEmail($email)) {
				throw new \InvalidArgumentException("E-mail inválido");
			}
			
			$cliente = new Cliente();
			$cliente->id = $_POST['id'];
			$cliente->nome = $nome;
			$cliente->cpf = $cpf;
			$cliente->email = $email;
			$cliente->telCelular = trim($_POST['telcelular']);
			$cliente->telResidencial = trim($_POST['telresidencial']);
			$cliente->telComercial = trim($_POST['telcomercial']);
			
			if (empty($cliente->id)){
				$cliente->endereco = $this->getEndereco(new Endereco());
				$this->fachada->inserirCliente($cliente);
			} else {
				$cli = $this->fachada->buscarCliente($cliente);
				$cliente->endereco = $this->getEndereco($cli->endereco);
				$this->fachada->atualizarCliente($cliente);
			}
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		}catch (\Exception $ex){
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	/**
	 * @param Endereco $endereco
	 * @return Endereco
	 */
	private function getEndereco($endereco) {
		$cep = trim($_POST['cep']);
		$logradouro = trim($_POST['logradouro']);
		$numero = trim($_POST['numero']);
		$complemento = trim($_POST['complemento']);
		$bairro = trim($_POST['bairro']);
		$cidade = trim($_POST['cidade']);
		$estado = trim($_POST['estado']);
		
		if (!empty($cep))
			$endereco->cep = $cep;
		if (!empty($logradouro))
			$endereco->logradouro = $logradouro;
		if (!empty($numero))
			$endereco->numero = $numero;
		if (!empty($complemento))
			$endereco->complemento = $complemento;
		if (!empty($bairro))
			$endereco->bairro = $bairro;
		if (!empty($cidade))
			$endereco->cidade = $cidade;
		if (!empty($estado))
			$endereco->estado = $estado;
		
		return $endereco;
	}
	
	public function listarClientes() {
		try {
			
			$clientes = $this->fachada->listarTodosClientes();
			foreach ($clientes->toArray() as $cliente){
				$telefones = join('<br>', array($cliente->telCelular, 
						$cliente->telResidencial, $cliente->telComercial));
						
				$this->dataTables->addRow(array($cliente->id, $cliente->nome, $cliente->cpf,
						$telefones));
			}
			
			echo $this->dataTables;
			
		} catch (Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function buscarCliente() {
		try {
			
			if (empty($_POST['id'])){
				throw new \InvalidArgumentException("Favor selecionar um cliente");
			}
				
			$cliente = new Cliente();
			$cliente->id = $_POST['id'];
				
			$cliente = $this->fachada->buscarCliente($cliente);
			
			echo new JSONResponse(true, $cliente);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function excluirCliente() {
		try {
			
			if (empty($_POST['id'])) {
				throw new \Exception("Selecione um cliente.");
			}
				
			$cliente = new Cliente();
			$cliente->id = $_POST['id'];
				
			$this->fachada->excluirCliente($cliente);
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function buscarEndereco() {
		try {
				
			if (empty($_POST['cep'])) {
				throw new \Exception("Informe o CEP.");
			}
	
			$consulta = Correios::buscarEndereco($_POST['cep']);
			$endereco = new Endereco();
			$endereco->estado = $consulta['estado'];
			$endereco->cidade = $consulta['cidade'];
			$endereco->bairro = $consulta['bairro'];
			$endereco->logradouro = $consulta['logradouro'];
			
			echo new JSONResponse(true, $endereco);
				
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	private function setarBotoes(){
		if ($this->validarPermissao(Permissao::VISUALIZAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Visualizar', 'buscarCliente', 'clip-zoom-in', 'visualizar'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Editar', 'buscarCliente', 'icon-edit', 'editar'));
		}
		if ($this->validarPermissao(Permissao::REMOVER)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Excluir', 'excluirCliente', 'icon-remove icon-white', 'excluir'));
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
