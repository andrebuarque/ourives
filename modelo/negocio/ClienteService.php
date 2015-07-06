<?php
namespace modelo\negocio;

use Doctrine\Common\Collections\ArrayCollection;
use modelo\entidades\Perfil;
use Psr\Log\InvalidArgumentException;
use modelo\dao\ClienteDAO;
use modelo\entidades\Cliente;

/**
 * @author Buarque
 */
class ClienteService {
	
	/**
	 * @var ClienteDAO
	 */
	private $clienteDAO;
	
	public function __construct() {
		$this->clienteDAO = new ClienteDAO();
	}
	
	/**
	 * @param Cliente $cliente
	 * @throws \InvalidArgumentException
	 */
	public function inserirCliente(Cliente $cliente){
		return $this->clienteDAO->inserir($cliente);
	}
	
	/**
	 * @param Cliente $cliente
	 * @throws \InvalidArgumentException
	 */
	public function atualizarCliente(Cliente $cliente){
		return $this->clienteDAO->atualizar($cliente);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarTodosClientes(){
		return $this->clienteDAO->listarTodosClientes();
	}
	
	public function buscarCliente(Cliente $cliente){
		return $this->clienteDAO->buscar($cliente);
	}
	
	/**
	 * @param Cliente $cliente
	 */
	public function excluir(Cliente $cliente) {
		$cliente = $this->clienteDAO->buscar($cliente);
		$this->clienteDAO->excluir($cliente);
	}
	
}

?>