<?php
/**
 * Classe para upload de arquivos
 *
 * Feita para a manipulação mais 
 * eficiente de tipos de arquivos 
 * determinação de tamanho máximo
 * 
 */
class UploadFile {

    /**
    * @access privete
    */
    private $file = array();

    /**
    * @access public
    */
    public $dir = "";
     
    /**
     * @access public
     */    
    public $extension;
    
    /**
     * @access public
     */
    public $size;

    /**
    * Prepara os dados do $_FILES para uma variavel.
    * 
    * @param Array $_file
    */
    public function  __construct( $_file ){
    	
        foreach( $_FILES[ $_file ] as $key => $values ){
            $this->file[ $key ] = $values;
        }
    }

    /**
    * Faz o upload do arquivo
    */
    public function makeUpload(){
    	
        /**
         * Caso a variável dir, estiver vazia, ele retorna um erro
         */
        if($this->dir == ""){
            throw new UploadFileException( "Você deve determinar um caminho para os arquivos." );
        }

        if (!self::isFile()){
        	throw new UploadFileException( "O(s) arquivo(s) escolhido(s) não é(são) permitido(s)." );
        }
        
		if (!self::size($this->size)){
			throw new UploadFileException( "O(s) arquivo(s) é(são) acima do tamanho pré-determinado." );
		}
		
		if (self::isArray($this->file["error"])){
			try {
				
				foreach ($this->file["error"] as $key => $error){
					if ($error == UPLOAD_ERR_OK){
						// Inicia a cópia do arquivo
						$newFileName = date("dmYHis") . "_" . $this->file["name"][$key];
						move_uploaded_file($this->file["tmp_name"][$key], $this->dir . $newFileName);
						// retorna o nome do arquivo, para ser salvo no banco
						return $newFileName;
					}
				}
				
			}catch (\Exception $ex){
				throw new UploadFileException($ex->getMessage());
			}
		} else {
			try {
				// Inicia a cópia do arquivo
				$newFileName = date("dmYHis") . "_" . $this->file["name"];
				move_uploaded_file($this->file["tmp_name"], $this->dir . $newFileName);
				
				// retorna o nome do arquivo, para ser salvo no banco
				return $newFileName;
			}catch (\Exception $ex){
				throw new UploadFileException($ex->getMessage());
			}
		}
    }

    /**
     * Verifica se o arquivo é do tamanho determinado pelo programador.
     *
     * @param int $_max_size
     * @return Bool
     */
    private function size($_max_size){
    	$_max_size = self::convertMbToBt( $_max_size );
    
    	if ($this->isFile()){
    		if (self::isArray($this->file["size"])){
    			$count = count($this->file["size"]);
    			$counter = 0;
    
    			foreach ($this->file["size"] as $newSize){
    				($newSize <= $_max_size) ? $counter++ : $counter--;
    			}
    
    			return ( $counter == $count ) ? true : false ;
    		} else {
    			return ($this->file["size"] <= $_max_size) ? true : false;
    		}
    	}
    }

    /**
    * Verifica se o arquivo enviado é de uma das extensões permitidas.
    *
    * @return Bool
    */
    private function isFile(){
        if (self::isArray($this->extension)){
        	
            $extensions = implode("|", $this->extension);

            $_file_test = self::isArrayEmpty($this->file[ "name" ]);

            if (self::isArray($_file_test)){
				$count = count( $_file_test );
                $counter = 0;

                foreach($_file_test as $values){
                    (preg_match("/.+\.({$extensions})/", $values)) ? $counter++ : $counter-- ;
                }
                
				return ( $count == $counter ) ? true : false ;
            } else {
				return (preg_match("/.+\.({$extensions})/", $_file_test)) ? true : false ;
            }
        }
    }

    /**
    * Verifica se existe algum campo vazio.
    *
    * @params Array $_array array de uma key do $_FILES
    * @return Array
    */
    private function isArrayEmpty($_array){
        if (is_array($_array)){
            $_array_search = array_search("", $_array);

            if (is_numeric($_array_search)){
            	unset($_array[$_array_search]);
            }
        }
		return $_array;
    }

    /**
    * Verifica se é array.
    *
    * @params Array $_array array de uma key do $_FILES
    * @return Bool
    */
    private function isArray($_array){
        return (is_array($_array)) ? true : false ;
    }

    /**
    * Transforma o valor em MB para Byte
    *
    * @params int $_size valor em MB do tamanho máximo
    * @return int
    */
    private function convertMbToBt($_size){
        return $_size * pow( 2, 1024);
    }
}

class UploadFileException extends \Exception{

	public function __construct($msg = "Falha no envio do arquivo"){
		parent::__construct($msg);
	}
}
?>