<?php
namespace modelo\dao;

use modelo\dao\exceptions\ProblemaAcessoDadosException;

use lib\FacilLogger;

use modelo\entidades\Permissao;
use modelo\entidades\Perfil;
use Doctrine\Common\Collections\Criteria;

class PermissaoDAO extends GenericDAO{
	
	const MSG_PROBLEMA_INSERIR = "Não foi possível incluir a permissão.";
	const MSG_PROBLEMA_EXCLUIR = "Não foi possível excluir a permissão.";
	
	/**
	 * @param Permissao $permissao
	 * @throws ProblemaAcessoDadosException
	 */
	public function inserir(Permissao $permissao){
		try{
			
			return parent::insert($permissao);
			
		}catch (\Exception $ex){
	
			$msgLogger = self::MSG_PROBLEMA_INSERIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_INSERIR);
		}
	}
	
	/**
	 * @param Permissao $permissao
	 * @throws ProblemaAcessoDadosException
	 */
	public function excluirPermissoesPerfil(Perfil $perfil) {
		try{
			
			$this->em->createQuery("DELETE FROM modelo\\entidades\\Permissao p WHERE p.perfil = :perfil")
					 ->setParameter(':perfil', $perfil)
					 ->execute();
				
		}catch (\Exception $ex){
		
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
	
	/**
	 * @param Perfil $perfil
	 * @throws ProblemaAcessoDadosException
	 * @return array Permissao
	 */
	public function getPermissoesPerfil(Perfil $perfil) {
		try{
			
			$sql = "SELECT MENU_TYPE.id, 
					       MENU_TYPE.titulo,
					       MENU_TYPE.idmenu AS idmenupai,
					       PERMISSAO.visualizar,
					       PERMISSAO.gravar,
					       PERMISSAO.remover
					FROM   PERMISSAO 
					       INNER JOIN MENU_TYPE 
					               ON MENU_TYPE.id = PERMISSAO.idmenu 
					WHERE  PERMISSAO.idperfil = ?
						   AND MENU_TYPE.ativo = TRUE 
					ORDER BY MENU_TYPE.idmenu DESC";
			
			$stmt = $this->em->getConnection()->prepare($sql);
			$stmt->bindValue(1, $perfil->id);
			$stmt->execute();
			
			$registros = $stmt->fetchAll();
			
			return $registros;
		
		}catch (\Exception $ex){
		
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
}