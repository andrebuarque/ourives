<?php
namespace lib\DataTables;

class DataTables {
	
	private $dados;
	
	private $botoesDataTables;
	
	private $checkBoxDataTables;
	
	public function __construct($checkBox = false){
		$this->dados = array();
		$this->botoesDataTables = array();
		
		$this->checkBoxDataTables = $checkBox;
	}
	
	public function addBotaoMenu(BotaoDataTable $botao){
		$this->dados = array();
		$this->botoesDataTables[] = $botao;
	}
	
	public function addRow($array){
		if ($this->checkBoxDataTables){
			array_unshift($array, 'checkdt');
		}
		
		if (self::isArray($array)){
			if (!empty($this->botoesDataTables)){
				$array[count(array_keys($array))] = $this->botoesDataTables;
			}
			$this->dados[] = $array;
		}
	}
	
	public function getRows(){
		return $this->dados;
	}
	
	private static function isArray($array){
		return is_array($array);
	}
	
	public function __toString(){
		return json_encode(array("aaData" => $this->dados));
	}
	
	public function __destruct(){
		unset($this->dados);
	}
}