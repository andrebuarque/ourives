<?php
namespace modelo\entidades;

/**
 * 
 * @author jafersonmonteiro
 *
 *@Entity
 *@Table(name="usuario")
 */
class Usuario {
	
	const ATIVO = 'ATIVO';
	const INATIVO = 'INATIVO';
	
	/**
	 * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="SEQUENCE")
     * @SequenceGenerator(sequenceName="USUARIO_id_seq", initialValue=1, allocationSize=100)
	 * @var IntegerType
	 */
	public $id;
	
	/**
	 * @Column(name="nome", type="string")
	 */
	public $nome;
	
	/**
	 * @Column(name="email", type="string")
	 */
	public $email;
	
	/**
	 * @Column(name="senha", type="string")
	 */
	public $senha;
	
	/**
	 * @Column(name="ativo", type="boolean")
	 */
	public $ativo;
	
	/**
     * @ManyToOne(targetEntity="Perfil", fetch="EAGER")
     * @JoinColumn(name="idperfil", referencedColumnName="id")
     * @var Perfil
     **/
	public $perfil;
	
	public function __construct(){
		
	}
	
}