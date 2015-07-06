<?php

class CSVtoArray {
	
	/**
	 * Caminho completo do arquivo CSV
	 * @var string
	 */
	private $_file;

	function __construct($file) {
		$this->_file = $file;
	}

	public function getConteudo() {
		
		if (!self::isFile($this->_file)){
			throw new CSVtoArrayException("O arquivo não foi encontrado, favor enviar novamente");
		}
		
		// Abrindo o arquivo CSV da planilha
		$data = file($this->_file,  FILE_SKIP_EMPTY_LINES | FILE_TEXT | FILE_IGNORE_NEW_LINES);
		foreach ($data as $linha) {
			$conteudo[] = explode(";", utf8_encode($linha));
		}
		return $conteudo;
	}
	
	private static function isFile($file){
		return file_exists($file);
	}
}

class CSVtoArrayException extends \Exception{
	
	public function __construct($msg = "Falha na leitura do arquivo CSV"){
		parent::__construct($msg);
	}
}
?>