<?php
namespace modelo\entidades;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Buarque
 *
 *@Entity
 *@Table(name="categoria_ordem_servico")
 */
class CategoriaOS {
	
	const ATIVO = 'ATIVO';
	const INATIVO = 'INATIVO';
	
	/**
	 * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @SequenceGenerator(sequenceName="categoria_ordem_servico_id_seq", initialValue=1, allocationSize=100)
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
	
}