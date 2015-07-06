<?php
namespace modelo\dao;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use lib\FacilLogger;
use modelo\dao\exceptions\ProblemaAcessoDadosException;
use modelo\entidades\CategoriaOS;
use modelo\entidades\OrdemServico;

/**
 * @author jafersonm
 */
class OrdemServicoDAO extends GenericDAO {
	
	const MSG_PROBLEMA_INSERIR = "Não foi possível incluir a ordem de serviço";
	const MSG_PROBLEMA_ATUALIZAR = "Não foi possível atualizar a ordem de serviço";
	const MSG_PROBLEMA_BUSCAR = "Não foi possível buscar a ordem de serviço";
	const MSG_PROBLEMA_LISTAR = "Não foi possível listar as ordens de serviço";
	const MSG_PROBLEMA_EXCLUIR = "Não foi possível excluir a ordem de serviço";
	
	/**
	 * @param OrdemServico $os
	 * @throws ProblemaAcessoDadosException
	 */
	public function inserir(OrdemServico $os){
		try{
			return parent::insert($os);
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_INSERIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_INSERIR);
		}
	}
	
	/**
	 * @param OrdemServico $os
	 * @throws ProblemaAcessoDadosException
	 */
	public function atualizar(OrdemServico $os){
		try{
	
			return parent::update($os);
	
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
	
			return parent::findAll(new OrdemServico());
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_LISTAR);
		}
	}
	
	/**
	 * @param OrdemServico $os
	 * @throws ProblemaAcessoDadosException
	 * @return OrdemServico
	 */
	public function buscar(OrdemServico $os){
		try{
			
			return parent::find($os, $os->id);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 */
	public function excluir(OrdemServico $os){
		try{
				
			parent::delete($os);
			
		} catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . " - " . $ex->getMessage();
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
	
}

?>