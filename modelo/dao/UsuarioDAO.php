<?php
namespace modelo\dao;

use modelo\dao\exceptions\ProblemaAcessoDadosException;
use lib\FacilLogger;
use Doctrine\Common\Collections\ArrayCollection;
use modelo\entidades\Usuario;
use modelo\entidades\Perfil;
use Doctrine\Common\Collections\Criteria;

class UsuarioDAO extends GenericDAO {

	const MSG_PROBLEMA_INSERIR = "Não foi possível incluir o usuário";
	const MSG_PROBLEMA_ATUALIZAR = "Não foi possível atualizar o usuário";
	const MSG_PROBLEMA_BUSCAR = "Não foi possível buscar o usuário";
	const MSG_PROBLEMA_LISTAR = "Não foi possível listar os usuários";
	const MSG_PROBLEMA_EXCLUIR = "Não foi possível excluir o usuário";
	const MSG_PROBLEMA_AUTENTICAR = "E-mail e/ou senha inválidos.";
	/**
	 * Inserir usuário
	 * @param Usuario $usuario
	 * @throws ProblemaAcessoDadosException
	 */
	public function inserir(Usuario $usuario){
		try{
			return parent::insert($usuario);
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_INSERIR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_INSERIR);
		}
	}
	
	/**
	 * Busca por um usuário para obter autenticação para o sistema
	 * @param Usuario $usuario
	 * @return Usuario
	 */
	public function autenticar(Usuario $usuario) {
		try {
			
			$criteria = array(
				"email" => $usuario->email, 
				"senha" => $usuario->senha, 
				"ativo" => $usuario->ativo
			);

			return parent::findBy($usuario, $criteria);
			
		} catch (\Exception $ex) {
			$msgLogger = self::MSG_PROBLEMA_AUTENTICAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_AUTENTICAR);
		}
	}
	
	/**
	 * atualizar um usuário
	 * @param Usuario $usuario
	 * @throws ProblemaAcessoDadosException
	 * @return Usuario
	 */
	public function atualizar(Usuario $usuario){
		try{
	
			return parent::update($usuario);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_ATUALIZAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_ATUALIZAR);
		}
	}
	
	/**
	 * @param Usuario $usuario
	 * @throws ProblemaAcessoDadosException
	 * @return Usuario $usuario
	 */
	public function buscar(Usuario $usuario){
		try{
	
			return parent::find($usuario, $usuario->id);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * Busca um usuário pelo e-mail
	 * 
	 * @param Usuario $usuario
	 * @throws ProblemaAcessoDadosException
	 * @return Usuario $usuario
	 */
	public function buscarPorEmail(Usuario $usuario){
		try{
	
			$criteria = array("email" => $usuario->email);
			return parent::findBy($usuario, $criteria);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * listar todos os usuários
	 * @throws ProblemaAcessoDadosException
	 * @return ArrayCollection
	 */
	public function listar(){
		try{
	
			return parent::findAll(new Usuario());
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_LISTAR);
		}
	}
	
	/**
	 * verifica se o usuário existe
	 * @param Usuario $usuario
	 * @throws ProblemaAcessoDadosException
	 * @return boolean
	 */
	public function existe(Usuario $usuario){
		try{
				
			$criteria = array("id" => $usuario->id);
			return parent::exist($usuario, $criteria);
	
		}catch (\Exception $ex){
	
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}

	/**
	 * verifica se o usuário existe atraves do e-mail
	 * @param Usuario $usuario
	 * @throws ProblemaAcessoDadosException
	 * @return boolean
	 */
	public function existePorEmail(Usuario $usuario){
		try{
	
			$criteria = array("email" => $usuario->email);
			return parent::exist($usuario, $criteria);
	
		}catch (\Exception $ex){
	
			$msgLogger = self::MSG_PROBLEMA_BUSCAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_BUSCAR);
		}
	}
	
	/**
	 * Excluir o usuário
	 * @param Usuario $usuario
	 * @throws ProblemaAcessoDadosException
	 */
	public function excluir(Usuario $usuario){
		try{
				
			parent::delete($usuario);
		}catch (\Exception $ex){
	
			$msgLogger = self::MSG_PROBLEMA_EXCLUIR . " - " . $ex->getMessage();
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_EXCLUIR);
		}
	}
	
	/**
	 * listar todos por perfil.
	 * @throws ProblemaAcessoDadosException
	 * @return ArrayCollection
	 */
	public function listarPorPerfil(Perfil $perfil){
		try{
			
			$criteria = Criteria::expr()->eq('perfil', $perfil);
			return parent::findWithCriteria(new Usuario(), $criteria);
	
		}catch (\Exception $ex){
			$msgLogger = self::MSG_PROBLEMA_LISTAR . FacilLogger::gerarLogException($ex);
			FacilLogger::getLogger()->error($msgLogger);
			throw new ProblemaAcessoDadosException(self::MSG_PROBLEMA_LISTAR);
		}
	}
	
}

?>