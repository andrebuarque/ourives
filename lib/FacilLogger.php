<?php
namespace lib;

use lib\IPlugin;

class FacilLogger{
		
	private function __construct(){

	}
	
	/**
	 * @return \Logger4PHP
	 */
	public static function getLogger(){
		include_once LIB . DS . 'log/Logger4PHP.php';
		return new \Logger4PHP();
	}
	
	public static function gerarLogException(\Exception $ex){
		$msg = " - " . $ex->getMessage();
		$msg .= " [Arquivo: " . $ex->getFile();
		$msg .= " - Linha: " . $ex->getLine() . " ]";
		return $msg;
	}
}