<?php
namespace modelo\entidades;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author jafersonmonteiro
 *
 *@Entity
 *@Table(name="menu_type")
 */
class Menu {
	
	const ADMINISTRACAO = 1;
	const USUARIOS = 2;
	const PERFIL = 3;
	const CLIENTES = 4;
	const PRODUTOS = 5;
	const CATEGORIA_OS = 6;
	const ORDENS_DE_SERVICO = 7;
	
	/**
	 * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="SEQUENCE")
     * @SequenceGenerator(sequenceName="MENU_TYPE_id_seq", initialValue=1, allocationSize=100)
	 * @var unknown_type
	 */
	public $id;
	
	/**
	 * @Column(name="titulo", type="string")
	 */
	public $titulo;
	
	/**
	 * @Column(name="link", type="string")
	 */
	public $link;
	
	/**
	 * @Column(name="ativo", type="boolean")
	 */
	public $ativo;
	
	/**
	 * @OneToMany(targetEntity="Menu", mappedBy="menuPai")
	 */
	public $menusFilhos;
	
	/**
     * @ManyToOne(targetEntity="Menu", inversedBy="menusFilhos")
     * @JoinColumn(name="idmenu", referencedColumnName="id")
     **/
	public $menuPai;
	
	public function __construct(){
		$this->menusFilhos = new ArrayCollection();
	}
}