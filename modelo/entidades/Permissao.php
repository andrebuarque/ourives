<?php
namespace modelo\entidades;

/**
 * @author jafersonmonteiro
 *
 * @Entity
 * @Table(name="permissao")
 */
class Permissao {
	
	const VISUALIZAR = 'visualizar';
	const GRAVAR = 'gravar';
	const REMOVER = 'remover';
	
	/**
	 * @Column(name="visualizar", type="boolean")
	 * @var unknown_type
	 */
	public $visualizar;

	/**
	 * @Column(name="gravar", type="boolean")
	 * @var unknown_type
	 */
	public $gravar;
	
	/**
	 * @Column(name="remover", type="boolean")
	 * @var unknown_type
	 */
	public $remover;

    /**
     * @Id
     * @ManyToOne(targetEntity="Menu", fetch="EAGER")
     * @JoinColumn(name="idmenu", referencedColumnName="id")
     * @var Menu
     **/
	public $menu;
	
	/**
	 * @Id
	 * @ManyToOne(targetEntity="Perfil", inversedBy="permissoes", fetch="LAZY")
	 * @JoinColumn(name="idperfil", referencedColumnName="id")
	 * @var Perfil
	 **/
	public $perfil;
	
	public function __construct(){
		
	}
	
}