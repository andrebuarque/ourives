<?php
namespace modelo\entidades;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Buarque
 *
 *@Entity
 *@Table(name="ordem_servico")
 */
class OrdemServico {
	
	const ATRASADO = 'ATRASADO';
	const ABERTO = 'ABERTO';
	const CANCELADO = 'CANCELADO';
	const ENTREGUE = 'ENTREGUE';
	const SERVICO_CONCLUIDO = "SERVIÇO CONCLUÍDO";
	
	/**
	 * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @SequenceGenerator(sequenceName="ordem_servico_id_seq", initialValue=1, allocationSize=100)
	 * @var unknown_type
	 */
	public $id;
	
	/**
	 * @Column(name="descricao", type="text")
	 */
	public $descricao;
	
	/**
	 * @Column(name="observacao", type="text")
	 */
	public $observacao;
	
	/**
	 * @Column(name="valor", type="decimal")
	 */
	public $valor;
	
	/**
	 * @Column(name="valorpago", type="decimal")
	 */
	public $valorPago;
	
	/**
	 * @Column(name="dataentrega", type="date")
	 */
	public $dataEntrega;
	
	/**
	 * @Column(name="datasolicitacao", type="date")
	 */
	public $dataSolicitacao;
	
	/**
	 * @Column(name="dataprevistaentrega", type="date")
	 */
	public $dataPrevistaEntrega;
	
	/**
	 * @Column(name="entregarpara", type="string")
	 */
	public $entregarPara;
	
	/**
	 * @Column(name="servico", type="string")
	 */
	public $servico;
	
	/**
	 * @Column(name="estado", type="string")
	 */
	public $estado;
	
	/**
	 * @OneToOne(targetEntity="Produto", cascade={"persist", "merge"}, fetch="EAGER")
	 * @JoinColumn(name="idproduto", referencedColumnName="id")
	 * @var Produto
	 */
	public $produto;
	
	/**
	 * @OneToOne(targetEntity="Cliente", cascade={"persist", "merge"}, fetch="EAGER")
	 * @JoinColumn(name="idcliente", referencedColumnName="id")
	 * @var Cliente
	 */
	public $cliente;
	
	/**
	 * @OneToOne(targetEntity="CategoriaOS", cascade={"persist", "merge"}, fetch="EAGER")
	 * @JoinColumn(name="idcategoria", referencedColumnName="id")
	 * @var CategoriaOS
	 */
	public $categoria;
	
	/**
	 * @OneToOne(targetEntity="Usuario", cascade={"persist", "merge"}, fetch="EAGER")
	 * @JoinColumn(name="idourives", referencedColumnName="id")
	 * @var Usuario
	 */
	public $ourives;
	
	/**
	 * @OneToMany(targetEntity="ImagemOS", mappedBy="ordemServico", fetch="EAGER", cascade="ALL")
	 * @var ArrayCollection
	 */
	public $imagens;
	
	/**
	 * @OneToOne(targetEntity="Usuario", cascade={"persist", "merge"}, fetch="EAGER")
	 * @JoinColumn(name="idusuario_solicitacao", referencedColumnName="id")
	 * @var Usuario
	 */
	public $usuarioSolicitacao;
	
	/**
	 * @Column(name="dataalteracao", type="date")
	 */
	public $dataAlteracao;
	
	/**
	 * @OneToOne(targetEntity="Usuario", cascade={"persist", "merge"}, fetch="EAGER")
	 * @JoinColumn(name="idusuario_alteracao", referencedColumnName="id")
	 * @var Usuario
	 */
	public $usuarioAlteracao;
	
	/**
	 * @OneToOne(targetEntity="Usuario", cascade={"persist", "merge"}, fetch="EAGER")
	 * @JoinColumn(name="idusuariofinalizacao", referencedColumnName="id")
	 * @var Usuario
	 */
	public $usuarioFinalizacao;
	
	public function __construct() {
		$this->imagens = new ArrayCollection();
	}
	
	public function addFoto($foto) {
		$this->imagens[] = $foto;
	}
	
}