<?php
namespace modelo\negocio;

use modelo\dao\MenuDAO;
use modelo\entidades\Menu;

class MenuService {
	
	private $menuDAO;
	
	public function __construct(){
		$this->menuDAO = new MenuDAO();
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarMenu(){
		return $this->menuDAO->listar();
	}
	

	public function buscarMenu(Menu $menu){
		return $this->menuDAO->buscarMenu($menu);
	}
	
}