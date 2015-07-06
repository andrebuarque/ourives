<?php
namespace modelo\negocio;

use modelo\dao\ProdutoDAO;
use modelo\entidades\Produto;
class ProdutoService {
	
	/** 
	 * @var ProdutoDAO
	 */
	private $produtoDAO;
	
	public function __construct(){
		$this->produtoDAO = new ProdutoDAO();
	}
	
	/**
	 * @param Produto $produto
	 */
	public function inserir(Produto $produto){
		return $this->produtoDAO->inserir($produto);
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function listarProdutos(){
		return $this->produtoDAO->listarTodosProdutos();
	}
	
	/**
	 * @param Produto $produto
	 */
	public function atualizar(Produto $produto){
		$this->produtoDAO->atualizar($produto);
	}
	
	/**
	 * @param Produto $produto
	 * @return \modelo\entidades\Produto
	 */
	public function buscar(Produto $produto){
		return $this->produtoDAO->buscar($produto);
	}
	
	/**
	 * @param Produto $produto
	 */
	public function excluir(Produto $produto){
		$produto = $this->buscar($produto);
		$this->produtoDAO->excluir($produto);
	}
}