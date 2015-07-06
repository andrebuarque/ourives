<?php
namespace modelo\entidades;

/**
 * 
 * @author Buarque
 *
 *@Entity
 *@Table(name="imagem_ordem_servico")
 */
class ImagemOS {

	const PATH = "/visao/default/img/imagens_os/";
	
	/**
	 * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="SEQUENCE")
     * @SequenceGenerator(sequenceName="imagem_ordem_servico_id_seq", initialValue=1, allocationSize=100)
	 * @var IntegerType
	 */
	public $id;
	
	/**
	 * @Column(name="titulo", type="string")
	 */
	public $titulo;
	
	/**
	 * @ManyToOne(targetEntity="OrdemServico", inversedBy="imagens", fetch="EAGER")
	 * @JoinColumn(name="idordemservico", referencedColumnName="id")
	 * @var OrdemServico
	 **/
	public $ordemServico;
	
	public function __construct(){
		
	}
	
}