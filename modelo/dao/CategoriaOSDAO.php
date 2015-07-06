<?php
namespace modelo\dao;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use lib\FacilLogger;
use modelo\dao\exceptions\ProblemaAcessoDadosException;
use modelo\entidades\CategoriaOS;

/**
 * @author jafersonm
 */
class CategoriaOSDAO extends GenericDAO {
	
	const MSG_PROBLEMA_INSERIR = "Não foi possível incluir a categoria";
	const MSG_PROBLEMA_ATUALIZAR = "Não foi possível atualizar a categoria";
	const MSG_PROBLEMA_BUSCAR = "Não foi possível buscar a categoria";
	const MSG_PROBLEMA_LISTAR = "Não foi possível listar as categorias";
	const MSG_PROBLEMA_EXCLUIR = "Não foi possível excluir a categoria";
	
	/**
	 * @param CategoriaOS $categoriaOS
	 * @throws ProblemaAcessoDadosException
	 */
	public function inserir(CategoriaOS $categoriaOS){
		try{
			return parent::insert($categoriaOS);
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_INSERIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_INSERIR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 */
	public function listarAtivas() {
		try {

			$criteria = Criteria::expr()->eq('ativo', 'true');
			return parent::findWithCriteria(new CategoriaOS(), $criteria);
			
		} catch (\Exception $e) {
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}		
	
	/** 
	 * @param CategoriaOS $categoriaOS
	 * @throws ProblemaAcessoDadosException
	 * @return CategoriaOS $categoriaOS
	 */
	public function atualizar(CategoriaOS $categoriaOS){
		try{
	
			return parent::update($categoriaOS);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_ATUALIZAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_ATUALIZAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 * @return ArrayCollection
	 */
	public function listar(){
		try{
	
			return parent::findAll(new CategoriaOS());
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_LISTAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 * @return Perfil
	 */
	public function buscar(CategoriaOS $categoriaOS){
		try{
	
			return parent::find($categoriaOS, $categoriaOS->id);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @param CategoriaOS $categoriaOS
	 * @throws ProblemaAcessoDadosException
	 * @return boolean
	 */
	public function existe(CategoriaOS $categoriaOS){
		try{
			
			$criteria = array("id" => $categoriaOS->id);
			return parent::exist($categoriaOS, $criteria);
			
		} catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 */
	public function excluir(CategoriaOS $categoriaOS){
		try{
				
			parent::delete($categoriaOS);
			
		} catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . " - " . $ex->getMessage();
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
	
}

?>