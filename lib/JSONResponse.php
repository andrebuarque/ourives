<?php
namespace lib;

class JSONResponse {
	public $conteudo;
	public $status;

	public function __construct($status, $conteudo) {
		$this->status = $status;
		$this->conteudo = $conteudo;
	}
	
	public function __toString() {
		return json_encode($this);
	}
}