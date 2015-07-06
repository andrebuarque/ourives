<?php
namespace modelo\dao;

use modelo\entidades\Produto;
use lib\FacilLogger;
use modelo\dao\exceptions\ProblemaAcessoDadosException;
use Doctrine\Common\Collections\ArrayCollection;
class ProdutoDAO extends GenericDAO{
	
	const MSG_PROBLEMA_INSERIR = "Não foi possível incluir o produto";
	const MSG_PROBLEMA_ATUALIZAR = "Não foi possível atualizar o produto";
	const MSG_PROBLEMA_BUSCAR = "Não foi possível buscar o produto";
	const MSG_PROBLEMA_LISTAR = "Não foi possível listar os produto";
	const MSG_PROBLEMA_EXCLUIR = "Não foi possível excluir o produto";
	
	/**
	 * @param Produto $produto
	 * @throws ProblemaAcessoDadosException
	 */
	public function inserir(Produto $produto){
		try {
				
			return parent::insert($produto);
				
		} catch (\Exception $ex) {
			$msgLogger = self::MSG_PROBLEMA_INSERIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_INSERIR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 * @return ArrayCollection
	 */
	public function listarTodosProdutos() {
		try {
	
			return parent::findAll(new Produto());
				
		} catch (\Exception $e) {
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @param Produto $produto
	 * @throws ProblemaAcessoDadosException
	 * @return Produto $produto
	 */
	public function atualizar(Produto $produto){
		try{
	
			return parent::update($produto);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_ATUALIZAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_ATUALIZAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 * @return Produto
	 */
	public function buscar(Produto $produto){
		try{
	
			return parent::find($produto, $produto->id);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @param Produto $produto
	 * @throws ProblemaAcessoDadosException
	 * @return boolean
	 */
	public function existe(Produto $produto){
		try{
	
			$criteria = array("id" => $produto->id);
			return parent::exist($produto, $criteria);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 */
	public function excluir(Produto $produto){
		try{
	
			parent::delete($produto);
				
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . " - " . $ex->getMessage();
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
}