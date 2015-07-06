<?php
namespace modelo\dao;

use modelo\entidades\ImagemOS;
use lib\FacilLogger;
use modelo\dao\exceptions\ProblemaAcessoDadosException;
class ImagemOSDAO extends GenericDAO {
	
	const MSG_PROBLEMA_BUSCAR = "Não foi possível buscar a imagem";
	const MSG_PROBLEMA_EXCLUIR = "Não foi possível excluir a imagem";
	
	/**
	 * @throws ProblemaAcessoDadosException
	 * @return ImagemOS
	 */
	public function buscar(ImagemOS $imagemOS){
		try{
	
			return parent::find($imagemOS, $imagemOS->id);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 */
	public function excluir(ImagemOS $imagemOS){
		try{
	
			parent::delete($imagemOS);
				
		} catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . " - " . $ex->getMessage();
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
}