<?php
namespace controlador;

use fachada\Fachada;
use modelo\entidades\Menu;
use modelo\entidades\Usuario;
use lib\DataTables\DataTables;
use modelo\entidades\OrdemServico;
use lib\util\Util;
use lib\PDFPlugin;

class Home extends Modulo {
	
	const DIRETORIO_VISAO = "home";
	const DIRETORIO_IMPRESSAO_OS = "html/ordemservico/osimpressao.html";

	private $fachada;
	
	/**
	 * @var DataTables
	 */
	private $dataTables;
	
	/**
	 * @var Usuario
	 */
	private $usuarioLogado;
	
    public function __construct() {
        parent::__construct();
        try{
        	
        	$this->dataTables = new DataTables();
        	$this->usuarioLogado = $this->getUsuarioLogado();
        	$this->fachada = new Fachada();
        	Facil::setar("modulo", "");
        	
        }catch (\Exception $ex){
        	Facil::despacharErro(500, "Aplicação está indisponível no momento");
        }
    }

    public function index() {
    	try {
    		
    		$this->listarMenus();
    		$this->templatePlugin->carregarLayoutCompleto(self::DIRETORIO_VISAO);
    			
    	}catch (ControleException $ex){
    		Facil::despacharErro(404, "Página não encontrada");
    	}
    }

    public function sair() {
    	unset($_SESSION['usuario']);
    	$this->getUsuarioLogado();
    }
    
    public function listarRelatorioOS(){
    	try {
    		
    		$ordensDeServico = $this->fachada->listarOrdensServico();
    		foreach ($ordensDeServico as $ordemServico){
    			$estado = $this->tratarEstado($ordemServico->estado);
    			$titulo = (empty($ordemServico->servico)) ? "Produto: " . $ordemServico->produto->nome : "Serviço: " . $ordemServico->servico;
    			$this->dataTables->addRow(array($ordemServico->id, 
    									$ordemServico->cliente->nome,
    									$ordemServico->id,
    									$titulo,
    									$ordemServico->dataPrevistaEntrega->format("d/m/Y"),
    									$ordemServico->ourives->nome,
    									Util::formatarMoedaBrasil($ordemServico->valor),
    									$estado));
    		}
    		echo $this->dataTables;
    		
    	} catch (\Exception $ex) {
    		echo new JSONResponse(false, $ex->getMessage());
    	}
    }
    
    private function tratarEstado($tipoEstado){
    	$estado = '';
    	switch ($tipoEstado) {
    		case OrdemServico::ABERTO:
    			$estado = '<span class="label label-sm label-default">Aberto</span>';
    			break;
    		case OrdemServico::ATRASADO:
    			$estado = '<span class="label label-sm label-danger">Atrasado</span>';
    			break;
    		case OrdemServico::CANCELADO:
    			$estado = '<span class="label label-sm label-danger">Cancelado</span>';
    			break;
    		case OrdemServico::ENTREGUE:
    			$estado = '<span class="label label-sm label-success">Entregue</span>';
    			break;
    		case OrdemServico::SERVICO_CONCLUIDO:
    			$estado = '<span class="label label-sm label-success">Serviço Concluído</span>';
    			break;
    	}
    	return $estado;
    }
    
    public function buscarOS(){
    	try {
    		
    		if (empty($_POST['idos'])){
    			throw new \InvalidArgumentException("Favor informar a Ordem de Serviço");
    		}
    		
    		$ordemServico = new OrdemServico();
    		$ordemServico->id = $_POST['idos'];
    		
    		$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
    		$debito = $ordemServico->valor - $ordemServico->valorPago; 
    		$estado = $this->tratarEstado($ordemServico->estado);
    		$titulo = (empty($ordemServico->servico)) ? "Produto: " . $ordemServico->produto->nome : "Serviço: " . $ordemServico->servico;
    		$dataEntrega = empty($ordemServico->dataEntrega) ? "Entrega não realizada" : $ordemServico->dataEntrega->format("d/m/Y");
    		
    		echo json_encode(array("os"=> 
    				array("cliente" => $ordemServico->cliente->nome, 
    					"numero" => $ordemServico->id,
    					"datasolicitacao" => $ordemServico->dataSolicitacao->format("d/m/Y"),
    					"dataentrega" => $dataEntrega,
    					"ourives" => $ordemServico->ourives->nome,
    					"valor" => Util::formatarMoedaBrasil($ordemServico->valor),
    					"valorPago" => Util::formatarMoedaBrasil($ordemServico->valorPago),
    					"valorDebito" => Util::formatarMoedaBrasil($debito),
    					"situacao" => $estado,
    					"servico" => $titulo,
    					"descricao" => $ordemServico->descricao,
    					"observacao" => $ordemServico->observacao,
    					"categoria" => $ordemServico->categoria->titulo,
    					"dataprevistaentrega" => $ordemServico->dataPrevistaEntrega->format("d/m/Y"),
    					"entregarpara" => $ordemServico->entregarPara,
    					"usuariosolicitacao" => $ordemServico->usuarioSolicitacao->nome
    		)));
    		
    	} catch (\Exception $ex) {
    		echo new JSONResponse(false, $ex->getMessage());
    	}
    }
    
    public function gerarOSParaImpressao(){
    	try{
    		
    		if (empty($_POST['idos'])){
    			throw new \InvalidArgumentException("Favor informar a Ordem de Serviço");
    		}
    		 
    		$ordemServico = new OrdemServico();
    		$ordemServico->id = $_POST['idos'];
    		$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
    		
    		$endereco = $ordemServico->cliente->endereco;
    		$end = "";
    		if (!empty($endereco)) {
    			$end = !empty($endereco->logradouro) ? $endereco->logradouro : "";
    			$end .= !empty($endereco->numero) ? ', '.$endereco->numero : "";
    			$end .= !empty($endereco->complemento) ? ', '.$endereco->complemento : "";
    			$end .= !empty($endereco->bairro) ? ', '.$endereco->bairro : "";
    			$end .= !empty($endereco->cidade) ? ', '.$endereco->cidade : "";
    			$end .= !empty($endereco->estado) ? ', '.$endereco->estado : "";
    			$end .= !empty($endereco->cep) ? ', '.$endereco->cep : "";
    		}
    		$ordemServico->cliente->endereco = $end;
    		 
    		Facil::setar("ordemServico", $ordemServico);
    		
    		$html = Facil::despachar(self::DIRETORIO_IMPRESSAO_OS, true);
    		$pdf= new PDFPlugin();

    		$pdf->setPagina($html);    		
    		$pdf->printPDF('os_' . $ordemServico->id);
    		
    	}catch (\Exception $ex){
    		Facil::despacharErro(404, $ex->getMessage());
    	}
    }
    
    private function listarMenus() {
    	$perfilUsuarioLogado = $this->usuarioLogado->perfil;
    	$permissoes = $this->fachada->getPermissoesPerfil($perfilUsuarioLogado);
    	$menus = $this->fachada->listarMenu();
    
    	$arrIdMenusUsuario = array();
    	foreach ($permissoes as $permissao) {
    		$arrIdMenusUsuario[] = $permissao['id'];
    	}
    	Facil::setar("permissoes", $arrIdMenusUsuario);
    	Facil::setar("menus", $menus);
    }
}
