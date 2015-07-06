<?php
namespace modelo\negocio;

use modelo\dao\UsuarioDAO;
use modelo\entidades\Usuario;
use lib\util\Util;
use controlador\Facil;
use lib\PHPMailerPlugin;
use modelo\entidades\Perfil;

class UsuarioService {
	
	const MIN_CARACTERES_SENHA = 6;
	
	/**
	 * @var UsuarioDAO
	 */
	private $usuarioDAO;
	
	public function __construct() {
		$this->usuarioDAO = new UsuarioDAO();
	}
	
	/**
	 * Cadastrar um usuário
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function cadastrar(Usuario $usuario){
		$this->validaSeUsuarioExiste($usuario);
		
		$usuario->senha = Util::gerarSenha();
		$this->usuarioDAO->inserir($usuario);
		
		$this->enviarEmailCadastroUsuario($usuario);
		return $usuario;
	}
	
	/**
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function alterar(Usuario $usuario){
		
		$usuarioCadastrado = $this->usuarioDAO->buscar($usuario);
		if (empty($usuario->senha)) {
			$usuario->senha = $usuarioCadastrado->senha;
		}
		if ($usuario->email != $usuarioCadastrado->email){
			$this->validaSeUsuarioExiste($usuario);
		}
		
		$this->usuarioDAO->atualizar($usuario);
		return $usuario;
	}
	
	public function alterarDadosCadastrais(Usuario $usuario) {
		$usuarioCadastrado = $this->usuarioDAO->buscar($usuario);
		if (!empty($usuario->senha) 
				&& $usuario->senha != $usuarioCadastrado->senha 
				&& strlen($usuario->senha) < self::MIN_CARACTERES_SENHA) {
			throw new \Exception("A senha deve conter no mínimo " . self::MIN_CARACTERES_SENHA . " caracteres.");
		}
		if ($usuario->email != $usuarioCadastrado->email){
			$this->validaSeUsuarioExiste($usuario);
		}
		
		$this->usuarioDAO->atualizar($usuario);
		return $usuario;
	}
	
	/**
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function buscar(Usuario $usuario){
		return $this->usuarioDAO->buscar($usuario);
	}
	
	/**
	 * @param Usuario $usuario
	 */
	public function excluir(Usuario $usuario){
		$usuario = $this->usuarioDAO->buscar($usuario);
		return $this->usuarioDAO->excluir($usuario);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listar(){
		return $this->usuarioDAO->listar();
	}
	
	private function enviarEmailCadastroUsuario(Usuario $usuario){
		$phpMailerPlugin = new PHPMailerPlugin();
		$phpMailer = $phpMailerPlugin->carregar();
		
		// Captura o conteúdo do e-mail
		Facil::setar('usuario', $usuario);
		$html = Facil::despachar('html/usuario/email_cadastrousuario', TRUE);
		
		// Envia o e-mail
		$phpMailer->Subject = 'Sistema Ourives - Cadastro Realizado';
		$phpMailer->AddAddress($usuario->email, $usuario->nome);
		$phpMailer->Body = $html;
		$phpMailer->IsHTML(true);
		$phpMailer->Send();
	}
	
	public function enviarEmailEsqueciSenha(Usuario $usuario) {
		$phpMailerPlugin = new PHPMailerPlugin();
		$phpMailer = $phpMailerPlugin->carregar();
		
		$usuario = $this->usuarioDAO->buscarPorEmail($usuario);
		$usuario->senha = Util::gerarSenha();
		$this->usuarioDAO->atualizar($usuario);
		
		// Captura o conteúdo do e-mail
		Facil::setar('usuario', $usuario);
		$html = Facil::despachar('html/usuario/email_esquecisenha', TRUE);
		
		// Envia o e-mail
		$phpMailer->Subject = 'Sistema Ourives - Esqueci senha';
		$phpMailer->AddAddress($usuario->email, $usuario->nome);
		$phpMailer->Body = $html;
		$phpMailer->IsHTML(true);
		$phpMailer->Send();
	}
	
	/**
	 * @param Usuario $usuario
	 * @throws \Exception
	 */
	private function validaSeUsuarioExiste(Usuario $usuario){
		$existeUsuario = $this->usuarioDAO->existePorEmail($usuario);
		if ($existeUsuario){
			throw new \Exception("E-mail já cadastrado, favor alterar");
		}
	}
	
	/**
	 * Autentica para acesso ao sistema
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function autenticar(Usuario $usuario) {
		$usuario->ativo = true;
		return $this->usuarioDAO->autenticar($usuario);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarOurives(){
		$perfil = new Perfil();
		$perfil->id = Perfil::OURIVES;
		return $this->usuarioDAO->listarPorPerfil($perfil);
	}
}

?>