<?php
namespace modelo\dao;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use modelo\entidades\Perfil;
use lib\FacilLogger;
use modelo\dao\exceptions\ProblemaAcessoDadosException;

/**
 * @author jafersonm
 */
class PerfilDAO extends GenericDAO {
	
	const MSG_PROBLEMA_INSERIR = "Não foi possível incluir o perfil";
	const MSG_PROBLEMA_ATUALIZAR = "Não foi possível atualizar o perfil";
	const MSG_PROBLEMA_BUSCAR = "Não foi possível buscar o perfil";
	const MSG_PROBLEMA_LISTAR = "Não foi possível listar os perfis";
	const MSG_PROBLEMA_EXCLUIR = "Não foi possível excluir o perfil";
	
	/**
	 * @param Perfil $perfil
	 * @throws ProblemaAcessoDadosException
	 */
	public function inserir(Perfil $perfil){
		try{
			return parent::insert($perfil);
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_INSERIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_INSERIR);
		}
	}
	
	/**
	 * Enter description here ...
	 * @throws ProblemaAcessoDadosException
	 */
	public function listarPerfisAtivos() {
		try {

			$criteria = Criteria::expr()->andX(Criteria::expr()->eq('ativo', 'true'));
			return parent::findWithCriteria(new Perfil(), $criteria);
			
		} catch (\Exception $e) {
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}		
	
	/** 
	 * @param Perfil $perfil
	 * @throws ProblemaAcessoDadosException
	 * @return Perfil $perfil
	 */
	public function atualizar(Perfil $perfil){
		try{
	
			return parent::update($perfil);
	
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
			
			$criteria = Criteria::expr()->notIn("id", array(Perfil::ADMINISTRADOR, Perfil::OURIVES));
			return parent::findWithCriteria(new Perfil(), $criteria);
	
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
	public function buscar(Perfil $perfil){
		try{
	
			return parent::find($perfil, $perfil->id);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @param Perfil $perfil
	 * @throws ProblemaAcessoDadosException
	 * @return boolean
	 */
	public function existe(Perfil $perfil){
		try{
				
			$criteria = array("id" => $perfil->id);
			return parent::exist($perfil, $criteria);
	
		}catch (\Exception $ex){
	
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 */
	public function excluir(Perfil $perfil){
		try{
				
			parent::delete($perfil);
		}catch (\Exception $ex){
	
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . " - " . $ex->getMessage();
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
	
}

?>