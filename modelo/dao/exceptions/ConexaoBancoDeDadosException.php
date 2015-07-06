<?php
namespace modelo\dao\exceptions;

use lib\FacilLogger;
class ConexaoBancoDeDadosException extends \Exception{
	
	const MSG = "A aplicação está indisponível no momento.";
	
	public function __construct(\Exception $ex){
		FacilLogger::getLogger()->critical($ex->getMessage());
		parent::__construct(self::MSG);
	}
}