<?php
namespace modelo\dao\exceptions;

class ProblemaAcessoDadosException extends \Exception {
	
	public function __construct($msg) {
		parent::__construct($msg);
	}
}