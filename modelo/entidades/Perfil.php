<?php
namespace modelo\entidades;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author jafersonmonteiro
 *
 *@Entity
 *@Table(name="perfil")
 */
class Perfil {
	
	const ATIVO = 'ATIVO';
	const INATIVO = 'INATIVO';
	const ADMINISTRADOR = 1;
	const OURIVES = 2;
	
	/**
	 * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @SequenceGenerator(sequenceName="PERFIL_id_seq", initialValue=1, allocationSize=100)
	 * @var unknown_type
	 */
	public $id;
	
	/**
	 * @Column(name="titulo", type="string")
	 */
	public $titulo;
	
	/**
	 * @Column(name="ativo", type="boolean")
	 */
	public $ativo;
	
	/**
	 * @OneToMany(targetEntity="Permissao", mappedBy="perfil", fetch="EAGER", cascade="ALL")
	 * @var Doctrine\Common\Collections\ArrayCollection
	 */
	public $permissoes;
	
	public function __construct(){
		$this->permissoes = new ArrayCollection();
	}
}