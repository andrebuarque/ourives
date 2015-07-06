<?php
namespace lib;

class PDFPlugin implements IPlugin{
	
	/**
	 * 
	 * @var \mPDF
	 */
	private $mpfd;
	
	public function __construct(){
		$this->carregar();
			
		$this->mpfd = new \mPDF();
		$this->mpfd->charset_in='UTF-8';
		$this->mpfd->SetMargins(5, 5, 5);
	}
	
	/**
	 * Metodo para carregar a o path da biblioteca
	 */
	public function carregar(){
		include LIB . DS . 'MPDF56/mpdf.php';
	}
	
	/**
	 * Metodo para criar paginas
	 * @param string $html
	 */
	public function setPagina($html){
		$this->mpfd->AddPage();
		$this->mpfd->WriteHTML($html);
	}
	
	/**
	 * Metodo para gerar pdf
	 * @param string $nomeArquivo
	 */
	public function printPDF($nomeArquivo = ""){
		$this->mpfd->Output($nomeArquivo, 'D');
	}
}