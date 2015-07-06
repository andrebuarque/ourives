<?php
namespace controlador;

use fachada\Fachada;
use lib\JSONResponse;
use modelo\entidades\Usuario;
use modelo\entidades\Perfil;
use modelo\entidades\Menu;

class MeusDadosCadastrais extends Modulo {
	
	const DIRETORIO_VISAO = "meusdados/index";
	const MSG_OPERACAO_SUCESSO = "Operação realizada com sucesso!";
	
	/**
	 * @var Fachada
	 */
	private $fachada;
	
	/**
	 * @var Usuario
	 */
	private $usuarioLogado;
	
	public function __construct() {
		parent::__construct();
		
		try{
			
			$this->fachada = new Fachada();
			$this->usuarioLogado = $this->getUsuarioLogado();
			
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
	
	public function alterarDadosCadastrais(){
		try {
			
			$nome = trim($_POST['nome']);
			$email = trim($_POST['email']);
			$senhaAtual = trim($_POST['senhaAtual']);
			$novaSenha = trim($_POST['novaSenha']);
			$confirmarNovaSenha = trim($_POST['confirmarNovaSenha']);
			
			if (empty($nome)) {
				throw new \Exception("O nome é obrigatório.");
			}
			
			if (empty($email)) {
				throw new \Exception("O email é obrigatório.");
			}
			
			$usuarioLogado = $this->getUsuarioLogado();
			$usuarioLogado->nome = $nome;
			$usuarioLogado->email = $email;
			
			if (!empty($senhaAtual)) {
				if ($senhaAtual != $usuarioLogado->senha) {
					throw new \Exception("Senha atual não confere.");
				}
				if (empty($novaSenha)) {
					throw new \Exception("Informe a nova senha.");
				}
				if (empty($confirmarNovaSenha)) {
					throw new \Exception("Confirme a nova senha.");
				}
				if ($novaSenha != $confirmarNovaSenha) {
					throw new \Exception("Confirme a nova senha corretamente.");
				}
				
				$usuarioLogado->senha = $novaSenha;
			}
			
			$usuarioLogado = $this->fachada->alterarDadosCadastrais($usuarioLogado);
			$_SESSION['usuario'] = serialize($usuarioLogado);
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		}catch (\Exception $ex){
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	private function listarMenus() {
		$perfilUsuarioLogado = $this->usuarioLogado->perfil;
		$permissoes = $this->fachada->getPermissoesPerfil($perfilUsuarioLogado);
		$menus = $this->fachada->listarMenu();
	
		$arrIdMenusUsuario = array();
		foreach ($permissoes as $permissao) {
			$arrIdMenusUsuario[] = $permissao['id'];
		}
		Facil::setar("permissoes", $arrIdMenusUsuario);
		Facil::setar("menus", $menus);
	}
}
