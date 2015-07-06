<?php
namespace modelo\entidades;

/**
 * @author andrebuarque
 *
 *@Entity
 *@Table(name="cliente")
 */
class Cliente {
	
	/**
	 * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @SequenceGenerator(sequenceName="cliente_id_seq", initialValue=1, allocationSize=100)
	 * @var integer
	 */
	public $id;
	
	/**
	 * @Column(name="nome", type="string")
	 * @var string
	 */
	public $nome;
	
	/**
	 * @Column(name="cpf", type="string")
	 * @var string
	 */
	public $cpf;
	
	/**
	 * @Column(name="email", type="string")
	 * @var string
	 */
	public $email;
	
	/**
	 * @Column(name="telcelular", type="string")
	 * @var string
	 */
	public $telCelular;
	
	/**
	 * @Column(name="telresidencial", type="string")
	 * @var string
	 */
	public $telResidencial;
	
	/**
	 * @Column(name="telcomercial", type="string")
	 * @var string
	 */
	public $telComercial;
	
	/**
	 * @OneToOne(targetEntity="Endereco", cascade={"persist", "remove", "merge"}, fetch="EAGER")
	 * @JoinColumn(name="idendereco", referencedColumnName="id")
	 * @var Endereco
	 */
	public $endereco;
	
	public function __construct(){
		
	}
}