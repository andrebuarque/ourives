<?php
namespace modelo\dao\exceptions;

class ObjetoNaoEncontradoException extends \Exception{
	
	private static $msg = "Registo não encontrado";
	
	public function __construct(){
		parent::__construct(self::$msg);
	}
}