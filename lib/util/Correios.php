<?php
namespace lib\util;

class Correios {
	
	public static function buscarEndereco($cep){
		$resultado = @file_get_contents("http://cep.republicavirtual.com.br/web_cep.php?cep=$cep&formato=json");
		$resultado = json_decode($resultado);
		
		if (empty($resultado->resultado)) {
			throw new \Exception("CEP não encontrado.");
		}
		
		$arrRetorno['estado'] = $resultado->uf;
		$arrRetorno['cidade'] = $resultado->cidade;
		$arrRetorno['bairro'] = $resultado->bairro;
		$arrRetorno['logradouro'] = $resultado->tipo_logradouro . " " . $resultado->logradouro;
		
		return $arrRetorno;
	}
}

?>