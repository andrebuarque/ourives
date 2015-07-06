<?php
namespace lib;
use lib\IPlugin;

class UploadFilePlugin implements IPlugin{
	
	/**
	 * @var \UploadFile
	 */
	private $uploadFile;
	
	/** 
	 * @param string $_file
	 */
	public function __construct($_file){
		$this->carregar();
		
		$this->uploadFile = new \UploadFile($_file);
	}

	/**
	 * Metodo para carregar a o path da biblioteca
	 */
	public function carregar(){
		include LIB . DS . 'UploadFile/UploadFile.php';
	}
	
	/**
	 * metodo para setar o diretorio que o arquivo será salvo
	 * @param string $dir
	 */
	public function setDiretorio($dir){
		$this->uploadFile->dir = $dir;
	}
	
	/**
	 * Metodo para retornar o diretorio que o arquivo
	 * foi salvo
	 * @return string
	 */
	public function getDiretorio(){
		return $this->uploadFile->dir;
	}
	
	/**
	 * Metodo para setar as extensoes do arquivo
	 * @param array $extensoes
	 */
	public function setExtensao($extensoes){
		$this->uploadFile->extension = $extensoes;
	}
	
	/**
	 * Metodo para setar o tamanho permitido para o arquivo
	 * @param int $tamanho
	 */
	public function setTamanhoArquivo($tamanho){
		$this->uploadFile->size = $tamanho;
	}
	
	/**
	 * Metodo para salvar o arquivo
	 */
	public function salvarArquivo(){
		return $this->uploadFile->makeUpload();
	}
	
}