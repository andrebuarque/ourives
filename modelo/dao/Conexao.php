<?php
namespace modelo\dao;

use modelo\dao\exceptions\ConexaoBancoDeDadosException;

use lib\PDOPlugin;
use lib\DoctrinePlugin;

class Conexao {
	
	private $pdo;
	
	public function __construct() {
		try {
				
			$this->pdo = PDOPlugin::getInstance()->getPdo();
			
		}catch (\Exception $ex) {
			throw new ConexaoBancoDeDadosException($ex);
		}
	}
	
	/**
	 * @return PDO
	 */
	private function getPdo() {
		return $this->pdo;
	}
	
	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getEntityManager() {
		return DoctrinePlugin::getInstance($this->pdo)->em;
	}
}