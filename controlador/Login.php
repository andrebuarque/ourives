<?php

namespace controlador;
use fachada\Fachada;
use lib\JSONResponse;
use modelo\entidades\Usuario;
use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;

class Login extends Modulo {

	const DIRETORIO_VISAO = "login";
	const PAGINA_INICIAL = 'Home';
	
	/**
	 * @var Fachada
	 */
	private $fachada;
	
    public function __construct() {
        parent::__construct();
        
        try{
        	
        	$usuarioLogado = $this->isUsuarioLogado();
        	if ($usuarioLogado) {
        		Facil::redirecionar(self::PAGINA_INICIAL);
        	}
        	$this->fachada = new Fachada();
        		
        }catch (\Exception $ex){
        	Facil::despacharErro(500, "Aplicação está indisponível no momento");
        }
    }

    public function index() {
    	try {
    		
    		$this->templatePlugin->carregarLayout(self::DIRETORIO_VISAO);
    			
    	}catch (ControleException $ex){
    		Facil::despacharErro(404, "Página não encontrada");
    	}
    }
    
    public function autenticar() {
    	try {
			
			if (empty($_POST['email'])) {
				throw new \Exception('Informe o e-mail.');
			}
			
			if (empty($_POST['senha'])) {
				throw new \Exception('Informe a senha.');
			}

			$email = trim($_POST['email']);
			$senha = trim($_POST['senha']);
			
			$usuario = new Usuario();
			$usuario->email = $email;
			$usuario->senha = $senha;
			
			if (!empty($_POST['lembrarEmail'])) {
				// Expira em 30 dias
				setcookie("email", $email, time()+2592000, '/');
			} else {
				setcookie("email", null, time()-2592000, '/');
			}

			$usuario = $this->fachada->autenticar($usuario);

			$_SESSION['usuario'] = serialize($usuario);
			
			echo new JSONResponse(true, self::PAGINA_INICIAL);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
    }
    
    public function esqueciMinhaSenha() {
    	try {
    			
    		if (empty($_POST['email'])) {
    			throw new \Exception('Informe o e-mail.');
    		}
    	
    		$email = trim($_POST['email']);
    		
    		$usuario = new Usuario();
    		$usuario->email = $email;
    		 
    		$this->fachada->enviarEmailEsqueciSenha($usuario);
    		
    		echo new JSONResponse(true, 'A senha foi enviada pro e-mail informado!');
    			
    	} catch (\Exception $e) {
    		echo new JSONResponse(false, $e->getMessage());
    	}
    }
    
}
