<?php
namespace modelo\negocio;

use Doctrine\Common\Collections\ArrayCollection;
use modelo\entidades\Perfil;
use modelo\dao\PerfilDAO;
use Psr\Log\InvalidArgumentException;

/**
 * @author Buarque
 */
class PerfilService {
	
	/**
	 * @var PerfilDAO
	 */
	private $perfilDAO;
	
	public function __construct() {
		$this->perfilDAO = new PerfilDAO();
	}
	
	/**
	 * @param Perfil $perfil
	 * @throws \InvalidArgumentException
	 */
	public function inserirPerfil(Perfil $perfil){
		$this->valida($perfil);
		return $this->perfilDAO->inserir($perfil);
	}
	
	/**
	 * @param Perfil $perfil
	 * @throws \InvalidArgumentException
	 */
	public function atualizarPerfil(Perfil $perfil){
		$this->valida($perfil);
		return $this->perfilDAO->atualizar($perfil);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listar(){
		return $this->perfilDAO->listar();
	}
	
	public function buscarPerfil(Perfil $perfil){
		return $this->perfilDAO->buscar($perfil);
	}
	
	/**
	 * @param Perfil $perfil
	 */
	public function excluir(Perfil $perfil) {
		$perfil = $this->perfilDAO->buscar($perfil);
		$this->perfilDAO->excluir($perfil);
	}
	
	private function valida(Perfil $perfil) {
		if ($perfil->permissoes->count() == 0)
			throw new \InvalidArgumentException("Favor configurar as permiss�es para este perfil.");
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function listarPerfisAtivo(){
		return $this->perfilDAO->listarPerfisAtivos();
	}
	
}

?>