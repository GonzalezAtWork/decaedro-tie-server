<?
#Reporta erros e parses errôneos
error_reporting(E_ERROR | E_PARSE);

############################################################################

define('EMPTY_FILE_NAME', 1);
define('FILE_NOT_FOUND', 2);
define('BAD_FORMATTED_XML_STRING', 3);
define('EMPTY_XML_STRING', 4);
		
############################################################################

class XMLObject {

	private $xmlObject;
	
############################################################################

	public function __construct($string = NULL) {

		if (!empty($strFile)) {
			try {
	
				#Tenta carregar o XML como se fosse uma string
				$this->loadXMLFromString($string);
				
			} catch (Exception $e) {
	
				#Se identificar a exceção como string mal formatada
				if ($e->getCode() == BAD_FORMATTED_XML_STRING) {
					
					#Tenta carregar o XML como se fosse um arquivo
					$this->loadXMLFromFile($string);
						
				} else {
					die($e);
				}
	
			}
		}
	}

############################################################################
	
	public function getXMLObject() {
		return $this->xmlObject;	
	}

############################################################################

	public function getXMLElement($strElement) {
		$array = $this->xmlObject->xpath($strElement."[1]");
		if (empty($array)) {
			return NULL;
		} else {
			return $array[0][0];
		}
	}

############################################################################	

	public function loadXMLFromFile($fileName = NULL) {
		
		#Se o arquivo existe
		if (file_exists($fileName)) {

			#Limpa objeto
			$this->cleanUp();
			#Carrega XML do arquivo
			$this->xmlObject = simplexml_load_file($fileName);
			#Reatribui dados ao objeto
			$this->reassignObject();
						
		} else {
			if (empty($fileName)) {
				throw new Exception('Empty file name', EMPTY_FILE_NAME);
			} else {
				throw new Exception('File not found'.' - ['.$fileName.']', FILE_NOT_FOUND);
			}
		}

	}

############################################################################	

	public function loadXMLFromString($string = NULL) {
		
		if (!empty($string)) {

			#Limpa objeto
			$this->cleanUp();

			#Carrega XML do arquivo
			if($this->xmlObject = simplexml_load_string($string)) {
				#Reatribui dados ao objeto
				$this->reassignObject();			
			} else {
				throw new Exception("Bad formatted XML string", BAD_FORMATTED_XML_STRING);
			}
			
		} else {
			throw new Exception("Empty XML string", EMPTY_XML_STRING);
		}

	}

############################################################################	

	private function reassignObject() {
		#Reatribui as propriedades do objeto atual de acordo com o objeto carregado
		foreach (get_object_vars($this->xmlObject) as $key => $value) {
			$this->$key = $value;			
		}
		unset($this->xmlObject);
	}

############################################################################	

	private function cleanUp() {
		#Destrói todas as propriedades do objeto atual
		foreach (get_object_vars($this) as $key => $value) {
			$this->$key = NULL;			
		}
	}

############################################################################	

}
?>