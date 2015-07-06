<?
namespace lib\util;

class Validacao {

	/**
	 * @param string $cnpj
	 * @return boolean
	 */
	public static function isCNPJ($cnpj){
		$cnpj = preg_replace('/[^0-9]/', '', $cnpj);
		if (strlen($cnpj) <> 14){
			return false;
		}
		$calcular = 0;
		$calcularDois = 0;
		for ($i = 0, $x = 5; $i <= 11; $i++, $x--) {
			$x = ($x < 2) ? 9 : $x;
			$number = substr($cnpj, $i, 1);
			$calcular += $number * $x;
		}
		for ($i = 0, $x = 6; $i <= 12; $i++, $x--) {
			$x = ($x < 2) ? 9 : $x;
			$numberDois = substr($cnpj, $i, 1);
			$calcularDois += $numberDois * $x;
		}
	
		$digitoUm = (($calcular % 11) < 2) ? 0 : 11 - ($calcular % 11);
		$digitoDois = (($calcularDois % 11) < 2) ? 0 : 11 - ($calcularDois % 11);
	
		if ($digitoUm <> substr($cnpj, 12, 1) || $digitoDois <> substr($cnpj, 13, 1)) {
			return false;
		}
		return true;
	}
	
	/**
	 * @param string $cpf
	 * @return boolean
	 */
	public static function isCPF($cpf) {
		// Verifica se um número foi informado
	    if(empty($cpf)) {
	        return false;
	    }
	 
	    // Elimina possivel mascara
	    $cpf = preg_replace('/\D+/', '', $cpf);
	    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
	     
	    // Verifica se o numero de digitos informados é igual a 11 
	    if (strlen($cpf) != 11) {
	        return false;
	    }
	    // Verifica se nenhuma das sequências invalidas abaixo 
	    // foi digitada. Caso afirmativo, retorna falso
	    else if ($cpf == '00000000000' || 
	        $cpf == '11111111111' || 
	        $cpf == '22222222222' || 
	        $cpf == '33333333333' || 
	        $cpf == '44444444444' || 
	        $cpf == '55555555555' || 
	        $cpf == '66666666666' || 
	        $cpf == '77777777777' || 
	        $cpf == '88888888888' || 
	        $cpf == '99999999999') {
	        return false;
	     // Calcula os digitos verificadores para verificar se o
	     // CPF é válido
	     } else {
	        for ($t = 9; $t < 11; $t++) {
	            for ($d = 0, $c = 0; $c < $t; $c++) {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }
	            $d = ((10 * $d) % 11) % 10;
	            if ($cpf{$c} != $d) {
	                return false;
	            }
	        }
	        return true;
	    }
	}
	
	/**
	 * @param string $email
	 * @return number
	 */
	public static function isEmail($email) {
		// Verifica se o valor foi informado
		if(empty($email)) {
			return false;
		}
		
		return preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email);
	}
}
?>