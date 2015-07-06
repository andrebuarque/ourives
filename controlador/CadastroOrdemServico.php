<?php
namespace controlador;

use fachada\Fachada;
use lib\DataTables\DataTables;
use lib\DataTables\BotaoDataTable;
use lib\JSONResponse;
use modelo\entidades\Usuario;
use modelo\entidades\Perfil;
use modelo\entidades\Menu;
use modelo\entidades\Permissao;
use modelo\entidades\Cliente;
use lib\util\Validacao;
use lib\Log;
use modelo\entidades\Endereco;
use lib\util\Correios;
use modelo\entidades\OrdemServico;
use modelo\entidades\CategoriaOS;
use modelo\entidades\Produto;
use lib\util\Util;
use modelo\entidades\ImagemOS;

class CadastroOrdemServico extends Modulo {
	
	const DIRETORIO_VISAO = "ordemservico/index";
	const MSG_OPERACAO_SUCESSO = "Operação realizada com sucesso!";
	const MODULO = 'CLIENTE';
	
	/**
	 * @var DataTables
	 */
	private $dataTables;
	
	/**
	 * @var Fachada
	 */
	private $fachada;
	
	/**
	 * @var Usuario
	 */
	private $usuarioLogado;
	
	private $idmenu;
	
	private $cadastro;
	
	/**
	 * @var Menu
	 */
	private $menu;
	
	public function __construct() {
		parent::__construct();
		
		try{
			
			$this->idmenu = Menu::ORDENS_DE_SERVICO;
			
			$this->usuarioLogado = $this->getUsuarioLogado();
			
			$this->fachada = new Fachada();
			$this->dataTables = new DataTables();
			$this->cadastro = false;
			$this->setarBotoes();
			
			$menu = new Menu();
			$menu->id = $this->idmenu;
			$this->menu = $this->fachada->buscarMenu($menu);
				
			Facil::setar("modulo", $this->menu);
			
		}catch (\Exception $ex){
			Facil::despacharErro(500, "Aplicação está indisponível no momento");
		}
	}
	
	public function index() {
		try {
			$this->listarMenus();
			
			$perfis = $this->fachada->listarPerfisAtivos();
			$clientes = $this->fachada->listarTodosClientes();
			$categoriasOS = $this->fachada->listarCategoriasOSAtivas();
			$produtos = $this->fachada->listarProdutos();
			$ourives = $this->fachada->listarOurives();
			Facil::setar("clientes", $clientes);
			Facil::setar("categorias", $categoriasOS);
			Facil::setar("produtos", $produtos);
			Facil::setar("ourives", $ourives);
			Facil::setar("perfisUsuario", $perfis->toArray());
			
			$this->templatePlugin->carregarLayoutCompleto(self::DIRETORIO_VISAO);
			
		}catch (ControleException $ex){
			Facil::despacharErro(404, "Página não encontrada");
		}
	}
	
	private function uploadImagem($caminho, $img) {
		$image = base64_decode(str_replace('data:image/jpeg;base64,', '', $img));
		$fp = fopen($caminho, 'w');
		fwrite($fp, $image);
		fclose($fp);
	}
	
	public function cadastrar(){
		try {
			
			$id = trim($_POST['id']);
			$idcategoria = trim($_POST['categoria']);
			$idourives = trim($_POST['ourives']);
			$idproduto = trim($_POST['produto']);
			$servico = trim($_POST['titulo']);
			$idcliente = trim($_POST['cliente']);
			$valor = floatval(trim($_POST['valor']));
			$dataprevistaentrega = trim($_POST['dataprevistaentrega']);
			$entregarpara = trim($_POST['entregarpara']);
			$observacao = trim($_POST['observacao']);
			$valorpago = floatval(trim($_POST['valorpago']));
			
			$categoria = new CategoriaOS();
			$categoria->id = $idcategoria;
			$categoria = $this->fachada->buscarCategoriaOS($categoria);
			
			$ourives = new Usuario();
			$ourives->id = $idourives;
			$ourives = $this->fachada->buscarUsuario($ourives);
			
			$cliente = new Cliente();
			$cliente->id = $idcliente;
			$cliente = $this->fachada->buscarCliente($cliente);
			
			$ordemServico = new OrdemServico();
			$ordemServico->id = $id;
			if (!empty($ordemServico->id)){
				$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
			}
			$ordemServico->categoria = $categoria;
			$ordemServico->ourives = $ourives;
			$ordemServico->observacao = $observacao;
			
			if (!empty($idproduto)) {
				$produto = new Produto();
				$produto->id = $idproduto;
				$produto = $this->fachada->buscarProduto($produto);
				$ordemServico->produto = $produto;
			}
			
			$ordemServico->cliente = $cliente;
			$ordemServico->servico = $servico;
			$ordemServico->valor = $valor;
			$ordemServico->valorPago = $valorpago;
			$ordemServico->dataPrevistaEntrega = new \DateTime(Util::formartarDataBanco($dataprevistaentrega));
			$ordemServico->entregarPara = $entregarpara;
			
			if (empty($ordemServico->id)){
				$ordemServico->dataSolicitacao = new \DateTime("now");
				$ordemServico->usuarioSolicitacao = $this->fachada->buscarUsuario($this->getUsuarioLogado());
				$ordemServico->estado = OrdemServico::ABERTO;
				$this->fachada->inserirOrdemServico($ordemServico);
			} else {
				$ordemServico->dataAlteracao = new \DateTime("now");
				$ordemServico->usuarioAlteracao = $this->fachada->buscarUsuario($this->getUsuarioLogado());
				$this->fachada->atualizarOrdemServico($ordemServico);
			}

			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		}catch (\Exception $ex){
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function listar() {
		try {
			
			$ordensServico = $this->fachada->listarOrdensServico();
			foreach ($ordensServico->toArray() as $ordemServico){
				$titulo = $ordemServico->produto != null ? 
					$ordemServico->produto->nome : $ordemServico->servico;
				
				$cliente = $ordemServico->cliente != null ? 
					$ordemServico->cliente->nome : '';
				
				$ourives = $ordemServico->ourives != null ? 
					$ordemServico->ourives->nome : '';
				
				$dataSolicitacao = $ordemServico->dataSolicitacao != null ? 
					$ordemServico->dataSolicitacao->format('d/m/Y') : '';
				
				$dataPrevistaEntrega = $ordemServico->dataPrevistaEntrega != null ? 
					$ordemServico->dataPrevistaEntrega->format('d/m/Y') : '';
				
				$titulo = (empty($ordemServico->servico)) ? "Produto: " . $ordemServico->produto->nome : "Serviço: " . $ordemServico->servico;
				
				$this->dataTables->addRow(array($ordemServico->id, $ordemServico->id, $titulo, $cliente,
						$dataSolicitacao,
						$dataPrevistaEntrega,
						$ourives,
						$ordemServico->valor, 
						$ordemServico->estado));
				
			}
			
			echo $this->dataTables;
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function buscar() {
		try {
			
			if (empty($_POST['id'])){
				throw new \InvalidArgumentException("Favor selecionar uma OS.");
			}
				
			$ordemServico = new OrdemServico();
			$ordemServico->id = $_POST['id']; 
			
			$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
			$ordemServico->dataPrevistaEntrega = $ordemServico->dataPrevistaEntrega->format('d/m/Y');
			$ordemServico->valor = $ordemServico->valor;
			$ordemServico->valorPago = $ordemServico->valorPago;
			
			echo new JSONResponse(true, $ordemServico);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function buscarImagensOS(){
		try{
			
			if (empty($_POST['id'])){
				throw new \InvalidArgumentException("Favor selecionar uma OS.");
			}
			
			$ordemServico = new OrdemServico();
			$ordemServico->id = $_POST['id'];
				
			$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
			echo json_encode(array("path" => BASE . "/visao/default/img/imagens_os/", "imagens" => $ordemServico->imagens->toArray()));
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function excluirImagemOS(){
		try{
				
			if (empty($_POST['id'])){
				throw new \InvalidArgumentException("Favor selecionar uma Imagem.");
			}
				
			$imagemOS = new ImagemOS();
			$imagemOS->id = $_POST['id'];
		
			$this->fachada->excluirImagemOS($imagemOS);
			echo new JSONResponse(true, "Imagem excluída com sucesso!");
				
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function excluir() {
		try {
			
			if (empty($_POST['id'])) {
				throw new \Exception("Selecione uma OS.");
			}
				
			$ordemServico = new OrdemServico();
			$ordemServico->id = $_POST['id'];
			
			$this->fachada->excluirOrdemServico($ordemServico);
			
			echo new JSONResponse(true, self::MSG_OPERACAO_SUCESSO);
			
		} catch (\Exception $e) {
			echo new JSONResponse(false, $e->getMessage());
		}
	}
	
	public function finalizarOS(){
		try {
			
			if (empty($_POST['idos'])) {
				throw new \InvalidArgumentException("Selecione uma OS.");
			}
			
			$ordemServico = new OrdemServico();
			$ordemServico->id = $_POST['idos'];
			
			$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
			
			if ($this->validaValorPago($ordemServico)){
				echo json_encode(array("status" => true, "erro" => "pendente"));
			} else {
				$ordemServico->estado = $ordemServico::ENTREGUE;
				$ordemServico->usuarioFinalizacao = $this->fachada->buscarUsuario($this->getUsuarioLogado());
				$this->fachada->atualizarOrdemServico($ordemServico);
				echo json_encode(array("status" => true, "erro" => "", "mensagem" => "OS Finalizada com sucesso!"));
			}
			
		} catch (\Exception $ex) {
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function alterarValorPago(){
		try {
			
			if (empty($_POST['idos'])) {
				throw new \InvalidArgumentException("Selecione uma OS.");
			}
				
			$ordemServico = new OrdemServico();
			$ordemServico->id = $_POST['idos'];
				
			$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
			$ordemServico->valorPago = floatval($_POST['valorPago']);
			
			// Valida o valor pago
			if ($ordemServico->valor > $ordemServico->valorPago) {
				echo json_encode(array("status" => true, "erro" => "O valor pago não pode ser inferior ao valor original."));
				return;
			}
			
			$ordemServico->usuarioFinalizacao = $this->fachada->buscarUsuario($this->getUsuarioLogado());
			$ordemServico->estado = OrdemServico::ENTREGUE;
			$this->fachada->atualizarOrdemServico($ordemServico);
			echo json_encode(array("status" => true, "erro" => "", "mensagem" => "OS Finalizada com sucesso!"));
			
		} catch (\Exception $ex) {
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function concluirServico(){
		try {
				
			if (empty($_POST['idos'])) {
				throw new \InvalidArgumentException("Selecione uma OS.");
			}
		
			$ordemServico = new OrdemServico();
			$ordemServico->id = $_POST['idos'];
		
			$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
			$ordemServico->estado = OrdemServico::SERVICO_CONCLUIDO;
				
			$ordemServico->usuarioAlteracao = $this->fachada->buscarUsuario($this->getUsuarioLogado());
			$this->fachada->atualizarOrdemServico($ordemServico);
			echo json_encode(array("status" => true, "erro" => "", "mensagem" => "Serviço concluído com sucesso!"));
				
		} catch (\Exception $ex) {
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function cancelarServico(){
		try {
	
			if (empty($_POST['idos'])) {
				throw new \InvalidArgumentException("Selecione uma OS.");
			}
	
			$ordemServico = new OrdemServico();
			$ordemServico->id = $_POST['idos'];
	
			$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
			$ordemServico->estado = OrdemServico::CANCELADO;
	
			$ordemServico->usuarioAlteracao = $this->fachada->buscarUsuario($this->getUsuarioLogado());
			$this->fachada->atualizarOrdemServico($ordemServico);
			echo json_encode(array("status" => true, "erro" => "", "mensagem" => "OS cancelada com sucesso!"));
	
		} catch (\Exception $ex) {
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	private function validaValorPago(OrdemServico $ordemServico) {
		return ($ordemServico->valor > $ordemServico->valorPago
					&& in_array($ordemServico->estado,
					array(OrdemServico::ABERTO, OrdemServico::ATRASADO)));
	}
	
	private function setarBotoes(){
		if ($this->validarPermissao(Permissao::VISUALIZAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Visualizar', 'buscar', 'clip-zoom-in', 'visualizar'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Editar', 'buscar', 'icon-edit', 'editar'));
		}
		if ($this->validarPermissao(Permissao::REMOVER)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Excluir', 'excluir', 'clip-remove', 'excluir'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Tirar Fotos', 'tirarFotos', 'clip-camera', 'tirarFotos'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Concluir Serviço', 'concluirServico', 'icon-thumbs-up', 'concluirServico'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Cancelar OS', 'cancelarOS', 'icon-remove', 'cancelarOS'));
		}
		if ($this->validarPermissao(Permissao::GRAVAR)) {
			$this->dataTables->addBotaoMenu(new BotaoDataTable('Finalizar OS', 'finalizarOS', 'clip-file-check', 'finalizarOS'));
		}
	}
	
	private function validarPermissao($acao) {
		$perfilUsuario = $this->usuarioLogado->perfil;
		$permissoes = $this->fachada->getPermissoesPerfil($perfilUsuario);
		foreach ($permissoes as $permissao) {
			if ($this->idmenu == $permissao['id']) {
				return $permissao[$acao];
				break;
			}
		}
		return false;
	}
	
	public function salvarImagem(){
		try{
			
			if (empty($_POST['idos'])){
				throw new \InvalidArgumentException("Favor selecionar a Ordem de Serviço");
			}
			
			$ordemServico = new OrdemServico();
			$ordemServico->id = $_POST["idos"];
			
			$ordemServico = $this->fachada->buscarOrdemServico($ordemServico);
			
			$titulo = md5(microtime()) . '.jpg';
			$this->uploadImagem(PATH_FISICO_IMAGENS_OS . $titulo, $_POST['url']);
				
			$imagem = new ImagemOS();
			$imagem->titulo = $titulo;
			$imagem->ordemServico = $ordemServico;
			$ordemServico->imagens->add($imagem);
			
			$this->fachada->atualizarOrdemServico($ordemServico);
			
			echo json_encode(array("status" => true, "urlFoto" => $_POST['url'], "idimagem" => $imagem->id));
			
		}catch (\Exception $ex){
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	public function listarClientes(){
		try{
			
			$tabelaClientes = new DataTables();
			
			$clientes = $this->fachada->listarTodosClientes();
			foreach ($clientes as $cliente){
				$tabelaClientes->addRow(array($cliente->id, $cliente->nome, $cliente->cpf, $cliente->email));
			}
			
			echo $tabelaClientes;
			
		} catch (\Exception $ex) {
			echo new JSONResponse(false, $ex->getMessage());
		}
	}
	
	private function listarMenus() {
		$perfilUsuarioLogado = $this->usuarioLogado->perfil;
		$permissoes = $this->fachada->getPermissoesPerfil($perfilUsuarioLogado);
		$menus = $this->fachada->listarMenu();		
		$this->cadastro = $this->validarPermissao(Permissao::GRAVAR);
	
		$arrIdMenusUsuario = array();
		foreach ($permissoes as $permissao) {
			$arrIdMenusUsuario[] = $permissao['id'];
		}
		Facil::setar("permissoes", $arrIdMenusUsuario);
		Facil::setar("menus", $menus);
		Facil::setar("cadastro", $this->cadastro);
	}
}
