<?php
namespace modelo\negocio;

use modelo\dao\ImagemOSDAO;
use modelo\entidades\ImagemOS;
class ImagemOSService {
	
	/**
	 * @var ImagemOSDAO
	 */
	private $imagemOSDAO;
	
	public function __construct() {
		$this->imagemOSDAO = new ImagemOSDAO();
	}
	
	/**
	 * @param ImagemOS $imagemOS
	 * @return \modelo\entidades\ImagemOS
	 */
	public function buscar(ImagemOS $imagemOS){
		return $this->imagemOSDAO->buscar($imagemOS);
	}
	
	/**
	 * @param ImagemOS $imagemOS
	 */
	public function excluir(ImagemOS $imagemOS){
		
		$imagemOS = $this->buscar($imagemOS);
		if (file_exists(PATH_FISICO_IMAGENS_OS . $imagemOS->titulo)){
			unlink(PATH_FISICO_IMAGENS_OS . $imagemOS->titulo);
		}
		$this->imagemOSDAO->excluir($imagemOS);
	}
	
}