<?php
namespace lib;

use controlador\Facil;

/**
 * Classe plugin para gerenciar os layout do sistema
 */
class TemplatePlugin {

	private $diretorio;
	
	private  $templateDefault;

	public function __construct(){
		$this->diretorio = "html";
		$this->templateDefault = array("includes/header", "includes/topo", "conteudo", "includes/footer");
	}

	/**
	 * Método utilitário para despachar para alguma view
	 *
	 * @var $conteudo Nome da tela que será chamada pelo Facil.
	 * Não é necessário fornecer a extensão do arquivo
	 * @var $retornar boolean Se true retorna em vez de imprimir na saída
	 *
	 */
	public function carregarLayout($conteudo, $retorno = false){
		$destino = $this->diretorio . "/" . $conteudo;
		$html = Facil::despachar($destino, true);
		if ($retorno) {
			return $html;
		} else {
			echo $html;
		}
	}

	/**
	 * Método utilitário para despachar varias views padrões.
	 *
	 * @var $conteudo Nome da tela que será chamada pelo Facil.
	 *
	 */
	public function carregarLayoutCompleto($conteudo){
		foreach ($this->templateDefault as $template){
			// seta o conteúdo passado por parametro
			if ($template == "conteudo"){
				$template = $conteudo;
			}
			$destino = $this->diretorio . "/" . $template;
			Facil::despachar($destino);
		}
	}
}
?>