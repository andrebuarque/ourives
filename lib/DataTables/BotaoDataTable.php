<?php
namespace lib\DataTables;

class BotaoDataTable {
	
	public $titulo;
	
	public $url;
	
	public $classIcone;
	
	public $acao;
	
	public function __construct($titulo, $url, $classIcone, $acao){
		$this->titulo = $titulo;
		$this->url = $url;
		$this->classIcone = $classIcone;
		$this->acao = $acao;
	}
	
}