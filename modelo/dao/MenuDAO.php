<?php
namespace modelo\dao;

use Doctrine\Common\Collections\ArrayCollection;

use modelo\dao\exceptions\ProblemaAcessoDadosException;

use lib\FacilLogger;

use modelo\entidades\Menu;
use Doctrine\Common\Collections\Criteria;

class MenuDAO extends GenericDAO{
	
	const MSG_PROBLEMA_INSERIR = "Ocorreu um problema ao incluir uma equipe";
	const MSG_PROBLEMA_ATUALIZAR = "Ocorreu um problema ao atualizar uma equipe";
	const MSG_PROBLEMA_BUSCAR = "Ocorreu um problema ao buscar uma equipe";
	const MSG_PROBLEMA_LISTAR = "Ocorreu um problema ao listar os menus";
	const MSG_PROBLEMA_EXCLUIR = "Ocorreu um problema ao excluir uma equipe";
	
	/**
	 * @throws ProblemaAcessoDadosException
	 * @return ArrayCollection
	 */
	public function listar(){
		try{
			
			$criteria = Criteria::create()
							->where(Criteria::expr()->eq('ativo', 'true'))
							->orderBy(array('id' => Criteria::ASC));
			
			$fqnObjeto = $this->getFQN(new Menu());
			$repositorio = $this->em->getRepository($fqnObjeto);
			
			return $repositorio->matching($criteria);
			
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_LISTAR);
		}
	}
	
	/**
	 * @throws ProblemaAcessoDadosException
	 * @return Menu $menu
	 */
	public function buscarMenu(Menu $menu){
		try{
	
			return parent::findBy($menu, array('id' => $menu->id));
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_LISTAR);
		}
	}
	
}