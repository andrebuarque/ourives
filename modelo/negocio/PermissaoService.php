<?php
namespace modelo\negocio;

use modelo\entidades\Permissao;

use modelo\dao\PermissaoDAO;
use modelo\entidades\Perfil;

class PermissaoService{
	
	private $permissaoDAO;
	
	public function __construct(){
		$this->permissaoDAO = new PermissaoDAO();
	}
	
	/**
	 * @param Permissao $permissao
	 */
	public function inserirPermissao(Permissao $permissao){
		return $this->permissaoDAO->inserir($permissao);
	}
	
	/**
	 * @param Perfil $perfil
	 */
	public function excluirPermissoesPerfil(Perfil $perfil){
		return $this->permissaoDAO->excluirPermissoesPerfil($perfil);
	}
	
	/**
	 * @param Perfil $perfil
	 * @return array Permissao
	 */
	public function getPermissoesPerfil(Perfil $perfil) {
		$permissoes = $this->permissaoDAO->getPermissoesPerfil($perfil);
		return $this->permissaoDAO->getPermissoesPerfil($perfil);
	}
	
}