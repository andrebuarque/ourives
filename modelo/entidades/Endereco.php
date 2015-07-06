<?php
namespace modelo\entidades;

/**
 * @author andrebuarque
 *
 *@Entity
 *@Table(name="endereco")
 */
class Endereco {

	/**
	 * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @SequenceGenerator(sequenceName="endereco_id_seq", initialValue=1, allocationSize=100)
	 * @var unknown_type
	 */
	public $id;
	
	/**
	 * @Column(name="logradouro", type="string")
	 */
	public $logradouro;
	
	/**
	 * @Column(name="numero", type="integer")
	 */
	public $numero;
	
	/**
	 * @Column(name="complemento", type="string")
	 */
	public $complemento;
	
	/**
	 * @Column(name="bairro", type="string")
	 */
	public $bairro;
	
	/**
	 * @Column(name="cidade", type="string")
	 */
	public $cidade;
	
	/**
	 * @Column(name="estado", type="string")
	 */
	public $estado;
	
	/**
	 * @Column(name="cep", type="string")
	 */
	public $cep;
	
	public function __construct(){
		
	}
}