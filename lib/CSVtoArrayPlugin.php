<?php
namespace lib;

class CSVtoArrayPlugin implements IPlugin{
	
	private $csvToArray;
	
	public function __construct($csv){
		$this->carregar();
		$this->csvToArray = new \CSVtoArray($csv);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \lib\IPlugin::carregar()
	 */
	public function carregar(){
		require_once LIB . DS . 'CSV/CSVtoArray.php';
	}
	
	public function getConteudo(){
		return $this->csvToArray->getConteudo();
	}
	
}