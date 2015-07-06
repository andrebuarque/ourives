<?php
namespace lib;

class FPDFPlugin implements IPlugin{

	private $pdf;

	public function __construct(){

	}
	/**
	 * (non-PHPdoc)
	 * @see \lib\IPlugin::carregar()
	 * @return \FPDF
	 */
	public function carregar(){
		require_once LIB . DS . 'FPDF/fpdf.php';
		$this->pdf = new \FPDF();
		return $this->pdf;
	}

}