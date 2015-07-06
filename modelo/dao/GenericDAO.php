<?php
namespace modelo\dao;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use modelo\dao\exceptions\ObjetoNaoEncontradoException;
use modelo\dao\exceptions\ProblemaAcessoDadosException;
use Doctrine\ORM\EntityManager;

abstract class GenericDAO{

	/**
	 *
	 * @var EntityManager
	 */
	protected $em;
	
	public function __construct(){
		$conexao = new Conexao();
		$this->em = $conexao->getEntityManager();
	}

	/**
	 * inserir um objeto no banco de dados
	 * @param objeto $objeto
	 */
	protected function insert($objeto){
		$this->em->persist($objeto);
		$this->em->flush();
	}
	
	/**
	 * Atualizar um objeto através da chave primaria
	 * @param unknown_type $objeto
	 */
	protected function update($objeto){
		$objeto = $this->em->merge($objeto);
		$this->em->flush();
		return $objeto;
	}

	/**
	 * buscar um objeto através da chave primaria
	 * @param Object $objeto
	 * @param string $attr
	 * @throws ObjetoNaoEncontradoException
	 * @return Object
	 */
	protected function find($objeto, $valor){
		// buscando o objeto
		$fqnObjeto = $this->getFQN($objeto);
		$novoObjeto = $this->em->find($fqnObjeto,
				$valor);

		if (empty($novoObjeto)){
			throw new ObjetoNaoEncontradoException();
		}
		
		return $novoObjeto;
	}

	/**
	 * Método para listar os objetos
	 * @param Object $objeto
	 * @return ArrayCollection
	 */
	protected function findAll($objeto){
		$fqnObjeto = $this->getFQN($objeto);
		$repositorio = $this->em->getRepository($fqnObjeto);
		$collection = new ArrayCollection($repositorio->findAll());
		return $collection;
	}
	
	/**
	 * Método para buscar objetos através de um criterio
	 * @param Objetc $objeto
	 * @param Array $criteria
	 * @throws ObjetoNaoEncontradoException
	 * @return Object
	 */
	protected function findBy($objeto, $criteria){
		$fqnObjeto = $this->getFQN($objeto);
		$repositorio = $this->em->getRepository($fqnObjeto);
		$novoObjeto = $repositorio->findOneBy($criteria);

		if (empty($novoObjeto)){
			throw new ObjetoNaoEncontradoException();
		}
		
		return $novoObjeto;
	}
	
	/**
	 * Método para verificar se um objeto existe através de um criterio
	 * @param Objetc $objeto
	 * @param Array $criteria
	 * @return boolean
	 */
	protected function exist($objeto, $criteria){
		$fqnObjeto = $this->getFQN($objeto);
		$repositorio = $this->em->getRepository($fqnObjeto);
		$novoObjeto = $repositorio->findOneBy($criteria);
	
		// TODO verificar por reflection se o objeto tem id
		if (!empty($novoObjeto)){
			$objeto->id = $novoObjeto->id;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Excluir objeto no banco de dados através da chave primaria
	 * @param Objetc $objeto
	 * @throws ProblemaAcessoDadosException
	 */
	protected function delete($objeto){
		$this->em->remove($objeto);
		$this->em->flush();
	}
	
	/**
	 * Metodo que retorna uma lista de objetos
	 * @param Objetc $objeto
	 * @param Criteria $criteria
	 * @return ArrayCollection
	 */
	protected function findWithCriteria($objeto, $criteria){
		$fqnObjeto = $this->getFQN($objeto);
		$repositorio = $this->em->getRepository($fqnObjeto);
		$collection = $repositorio->matching(Criteria::create()->where($criteria));
		
		return $collection;
	}
	
	/**
	 * Retorna o fully qualified name da classe
	 * @param Object $objeto
	 * @return string
	 */
	protected function getFQN($objeto){
		$reflection = new \ReflectionClass($objeto);
		$namespaceName = $reflection->getNamespaceName();
		$nomeObjeto = "\\" . $reflection->getShortName();
		return $namespaceName . $nomeObjeto;
	}
}