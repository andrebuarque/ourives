<?php
namespace modelo\negocio;

use Doctrine\Common\Collections\ArrayCollection;
use modelo\entidades\CategoriaOS;
use Psr\Log\InvalidArgumentException;
use modelo\dao\OrdemServicoDAO;
use modelo\entidades\OrdemServico;
use lib\PHPMailerPlugin;
use controlador\Facil;

/**
 * @author Buarque
 */
class OrdemServicoService {
	
	/**
	 * @var OrdemServicoDAO
	 */
	private $ordemServicoDAO;
	
	public function __construct() {
		$this->ordemServicoDAO = new OrdemServicoDAO();
	}
	
	/**
	 * @param OrdemServico $os
	 * @return OrdemServico
	 */
	public function inserir(OrdemServico $os){
		return $this->ordemServicoDAO->inserir($os);
	}
	
	/**
	 * @param OrdemServico $os
	 * @return OrdemServico
	 */
	public function atualizar(OrdemServico $os){
		return $this->ordemServicoDAO->atualizar($os);
	}
	
	/**
	 * @param OrdemServico $os
	 * @return \modelo\entidades\OrdemServico
	 */
	public function buscar(OrdemServico $os){
		return $this->ordemServicoDAO->buscar($os);
	}
	
	/**
	 * @param OrdemServico $os
	 */
	public function excluir(OrdemServico $os){
		$os = $this->ordemServicoDAO->buscar($os);
		$this->ordemServicoDAO->excluir($os);
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function listar(){
		return $this->ordemServicoDAO->listar();
	}
	
}

?>