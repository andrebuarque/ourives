<?php
namespace modelo\entidades;

/**
 * 
 * @author jafersonmonteiro
 *
 *@Entity
 *@Table(name = "produto")
 */
class Produto{
	
	/**
	 * @Id
	 * @Column(name="id", type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="produto_id_seq", initialValue=1, allocationSize=100)
	 * @var integer
	 */
	public $id;
	
	/**
	 * @Column(name = "nome", type="string")
	 */
	public $nome;
	
	/**
	 * @Column(name = "valor", type="string")
	 */
	public $valor;

	/**
	 * @Column(name="descricao", type="string")
	 */
	public $descricao;
	
	public function __construct(){
		
	}
	
}