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
use lib\PHPMailerPlugin;

class JobsOrdemServico {
	
	/**
	 * @var Fachada
	 */
	private $fachada;
	
	private $listaOS;
	
	public function __construct() {
		
		try{
			
			$this->fachada = new Fachada();
			$this->listaOS = $this->fachada->listarOrdensServico();
			
		}catch (\Exception $ex){
			Facil::despacharErro(500, "Aplicação está indisponível no momento");
		}
	}
	
	/**
	 * Atualiza o status da O.S. para ATRASADA caso o pagamento esteja pendente 
	 * e a data prevista para entrega esteja atrasada.
	 */
	public function atualizarStatusOS() {
		$totalOSAtrasadas = 0;
		$hoje = mktime(0,0,0,date('m'),date('d'),date('Y'));
		foreach ($this->listaOS as $ordemServico) {
			$anoEntrega = $ordemServico->dataPrevistaEntrega->format('Y');
			$diaEntrega = $ordemServico->dataPrevistaEntrega->format('d');
			$mesEntrega = $ordemServico->dataPrevistaEntrega->format('m');
			
			if ($hoje > mktime(0,0,0,$mesEntrega, $diaEntrega, $anoEntrega)
					&& $ordemServico->valorPago < $ordemServico->valor) {
				$ordemServico->estado = OrdemServico::ATRASADO;
				$this->fachada->atualizarOrdemServico($ordemServico);
			}
		}
	}
	
	/**
	 * Notifica o Ourives e o usuário que abriu a O.S. que faltam 
	 * apenas 5 dias para vencer a ordem de serviço.
	 */
	public function notificarUsuarioDataEntrega(){
		date_default_timezone_set("America/Recife");
		
		$phpMailerPlugin = new PHPMailerPlugin();
		$phpMailer = $phpMailerPlugin->carregar();
		$hoje = \DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
		
		foreach ($this->listaOS as $ordemServico) {
			$dataEntrega = \DateTime::createFromFormat('d/m/Y', $ordemServico->dataPrevistaEntrega->format('d/m/Y'));
			if ($dataEntrega->diff($hoje)->days == 5) {
				$usuarios = array($ordemServico->ourives, $ordemServico->usuarioSolicitacao);
				$this->enviarEmailLembreteDataEntrega($phpMailer, $usuarios, $ordemServico);
			}
		}
	}
	
	private function enviarEmailLembreteDataEntrega(\PHPMailer $phpMailer, $usuarios, $ordemServico) {
		foreach ($usuarios as $usuario) {
			if (empty($usuario))
				continue;
			// Captura o conteúdo do e-mail
			Facil::setar('usuario', $usuario);
			Facil::setar('os', $ordemServico);
			$html = Facil::despachar('html/ordemservico/email_lembrete_dataentrega', true);
			
			// Envia o e-mail
			$phpMailer->Subject = 'Sistema Ourives - Lembrete';
			$phpMailer->AddAddress($usuario->email, $usuario->nome);
			$phpMailer->Body = $html;
			$phpMailer->Send();
		}
	}
	
}
