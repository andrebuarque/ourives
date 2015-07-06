<?php
namespace controlador;

use lib\TemplatePlugin;
use fachada\Fachada;
use modelo\entidades\Usuario;
use modelo\entidades\Perfil;
use modelo\entidades\Menu;

class Modulo {
	
	protected $templatePlugin;
	
    public function __construct() {
    	$this->templatePlugin = new TemplatePlugin();
    }
    
    /**
     * Retorna o usuário que está logado no sistema.
     * 
     * @return NULL|Usuario
     */
    protected function getUsuarioLogado() {
    	if (!$this->isUsuarioLogado()){
    		Facil::redirecionar("Login");
    	}
    	
    	$usuarioLogado = unserialize($_SESSION['usuario']);
    	Facil::setar('usuarioLogado', $usuarioLogado);
    	return $usuarioLogado;
    }
    
    protected function isUsuarioLogado(){
    	return !empty($_SESSION['usuario']);
    }
    
}

?>