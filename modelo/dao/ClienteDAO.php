<?php
namespace modelo\dao;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use modelo\entidades\Perfil;
use lib\FacilLogger;
use modelo\dao\exceptions\ProblemaAcessoDadosException;
use modelo\entidades\Cliente;

/**
 * @author jafersonm
 */
class ClienteDAO extends GenericDAO {
	
	const MSG_PROBLEMA_INSERIR = "Não foi possível incluir o cliente";
	const MSG_PROBLEMA_ATUALIZAR = "Não foi possível atualizar o cliente";
	const MSG_PROBLEMA_BUSCAR = "Não foi possível buscar o cliente";
	const MSG_PROBLEMA_LISTAR = "Não foi possível listar os clientes";
	const MSG_PROBLEMA_EXCLUIR = "Não foi possível excluir o cliente";
	
	/**
	 * @param Cliente $cliente
	 * @throws ProblemaAcessoDadosException
	 */
	public function inserir(Cliente $cliente){
		try {
			
			return parent::insert($cliente);
			
		} catch (\Exception $ex) {
			$msgLogger = self::MSG_PROBLEMA_INSERIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_INSERIR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 */
	public function listarTodosClientes() {
		try {

			return parent::findAll(new Cliente());
			
		} catch (\Exception $e) {
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}		
	
	/** 
	 * @param Cliente $cliente
	 * @throws ProblemaAcessoDadosException
	 * @return Cliente $cliente
	 */
	public function atualizar(Cliente $cliente){
		try{
	
			return parent::update($cliente);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_ATUALIZAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_ATUALIZAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 * @return Cliente
	 */
	public function buscar(Cliente $cliente){
		try{
	
			return parent::find($cliente, $cliente->id);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @param Cliente $cliente
	 * @throws ProblemaAcessoDadosException
	 * @return boolean
	 */
	public function existe(Cliente $cliente){
		try{
				
			$criteria = array("id" => $cliente->id);
			return parent::exist($cliente, $criteria);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 */
	public function excluir(Cliente $cliente){
		try{
				
			parent::delete($cliente);
			
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . " - " . $ex->getMessage();
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
	
}

?>