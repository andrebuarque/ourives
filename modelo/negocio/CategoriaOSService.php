<?php
namespace modelo\negocio;

use Doctrine\Common\Collections\ArrayCollection;
use modelo\entidades\CategoriaOS;
use modelo\dao\CategoriaOSDAO;
use Psr\Log\InvalidArgumentException;

/**
 * @author Buarque
 */
class CategoriaOSService {
	
	/**
	 * @var CategoriaOSDAO
	 */
	private $categoriaOSDAO;
	
	public function __construct() {
		$this->categoriaOSDAO = new CategoriaOSDAO();
	}
	
	/**
	 * @param CategoriaOS $categoriaOS
	 * @throws \InvalidArgumentException
	 */
	public function inserir(CategoriaOS $categoriaOS){
		return $this->categoriaOSDAO->inserir($categoriaOS);
	}
	
	/**
	 * @param CategoriaOS $categoriaOS
	 * @throws \InvalidArgumentException
	 */
	public function atualizar(CategoriaOS $categoriaOS){
		return $this->categoriaOSDAO->atualizar($categoriaOS);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listar(){
		return $this->categoriaOSDAO->listar();
	}
	
	public function buscar(CategoriaOS $categoriaOS){
		return $this->categoriaOSDAO->buscar($categoriaOS);
	}
	
	/**
	 * @param CategoriaOS $categoriaOS
	 */
	public function excluir(CategoriaOS $categoriaOS) {
		$categoriaOS = $this->categoriaOSDAO->buscar($categoriaOS);
		$this->categoriaOSDAO->excluir($categoriaOS);
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function listarTodas(){
		return $this->categoriaOSDAO->listar();
	}
	
	public function listarAtivas() {
		return $this->categoriaOSDAO->listarAtivas();
	}
	
}

?>