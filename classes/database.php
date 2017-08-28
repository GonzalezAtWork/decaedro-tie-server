<?

//Classe singleton de acesso a banco de dados PostgreSQL

class Database {

	protected $connection, $query, $resource, $resultSet, $rows, $affectedRows, $insertedID, $arrayFields, $conn = null;

	//Salva uma instância única do banco de dados
	private static $instance;

	/* Construtor privado para limitar a intanciação do objeto ao escopo da classe */
	/* --------------------------------------------------------------------------------------------------------- */
	private function __construct( $BANCO = "" ) {
		if( $BANCO == "" ){
			$BANCO = $_SERVER["SERVER_NAME"];
		}
		$dbParameters = new XMLObject();
		// TROCAR ENDEREÇO PARA AMBIENTES:

		// HOMOLOGAÇÃO
		// $dbParameters->loadXMLFromFile($_SERVER['DOCUMENT_ROOT']."/SisManutHomologacao/config/".$_SERVER["SERVER_NAME"]."_database.xml");

		// PRODUCAO / DESENVOLVIMENTO LOCAL
		$dbParameters->loadXMLFromFile($_SERVER['DOCUMENT_ROOT']."/config/". $BANCO ."_database.xml");

		// DESENVOLVIMENTO MOBILE - TESTE
		//	$dbParameters->loadXMLFromFile($_SERVER['DOCUMENT_ROOT']."/Kalitera/config/".$_SERVER["SERVER_NAME"]."_database.xml");

		$this->query = NULL;
		$this->resource = NULL;
		$this->resultSet = NULL;
		$this->rows = NULL;
		$this->affectedRows = NULL;
		$this->insertedID = NULL;
		$this->debug = $dbParameters->debug;
		$this->arrayFields = array();
		
		$this->connection = @pg_connect(
			"host=".$dbParameters->host.
			" dbname=".$dbParameters->name.
			" port=".$dbParameters->port.
			" user=".$dbParameters->user.
			" password=".$dbParameters->password .
			" connect_timeout=15000"
		);

		$status = pg_connection_status($this->connection);

		if ($status === PGSQL_CONNECTION_OK) {
			$this->status = true;
		} else {
			$this->status = false;
			throw new Exception('Erro na Conexão com o Banco de Dados.');
		}
		
		return $this->status;

	}

	/* --------------------------------------------------------------------------------------------------------- */
	public final function __clone() {
    	//Pelo fato do sistema usar uma função recursiva, nesses casos é preciso permitir a clonagem e ignorar o singleton
	}

	/* Método getter para criar ou retornar a instância do singleton */
	/* --------------------------------------------------------------------------------------------------------- */
	public static function getInstance( $BANCO = "") {
		if($BANCO == "" ){
			if (!self::$instance) {
				self::$instance = new Database();
			}
			$retorno = self::$instance;
		}else{
			$retorno = new Database( $BANCO );
		}
		return $retorno;
	}

	/* Método setter para a query */
	/* --------------------------------------------------------------------------------------------------------- */
	public function setQuery($strQuery) {
		$this->query = $strQuery;
	}

	/* Método que executa a query */
	/* --------------------------------------------------------------------------------------------------------- */
	public function execute() {
		
		if ($this->query === NULL) {

			throw new Exception('Tentativa de executar query nula.');

		} else {

			$this->resource = pg_query($this->connection, $this->query);
			
			#Se o resource foi estabelecido com sucesso
			if ($this->resource != false) {
			
				$this->rows = pg_num_rows($this->resource);

				#Se tem resultado com linhas
				if ($this->rows > 0) {

					#retorna linhas
					$this->resultSet = pg_fetch_all($this->resource);

					#Itera campos do resource
					$i = pg_num_fields($this->resource);
					for ($j = 0; $j < $i; $j++) {
						array_push($this->arrayFields, pg_field_name($this->resource, $j));
					}

				} else {
					$this->resultSet = array();
				}
				
				if (pg_free_result($this->resource)) {
					return true;
				} else {
					return false;
				}
				
			} else {

				if (pg_last_error($this->connection) === false) {
					pg_free_result($this->resource);
					return true;
				} else {
					#Mostra último notice e último erro
					$error = pg_last_error($this->connection);
					pg_free_result($this->resource);

					if (empty($error)) {
						return true;
					} else {
						throw new Exception($error);
					}
				}
			}
		}
	}

	/* --------------------------------------------------------------------------------------------------------- */
	public function getResultSet() {
		return $this->resultSet;
	}

	/* --------------------------------------------------------------------------------------------------------- */
	public function getRows() {
		return $this->rows;
	}

	/* --------------------------------------------------------------------------------------------------------- */
	public function getErrorNumber() {
		return $this->errorNumber;
	}

	/* --------------------------------------------------------------------------------------------------------- */
	public function getErrorString() {
		return $this->errorString;
	}

	/* --------------------------------------------------------------------------------------------------------- */
	public function getFieldNames() {
		return $this->arrayFields;
	}

	/* --------------------------------------------------------------------------------------------------------- */
	public function getResultAsObject() {
		
		#Pega resultado da última query
		$array = $this->getResultSet();

		if (count($array) > 0) {
			#Como essa função espera um resultado único de uma tabela,
			#basta retornar a linha 0 do array convertida em objeto
			return (object) $array[0];
		} else {
			return NULL;
		}

	}
	/* --------------------------------------------------------------------------------------------------------- */
}
?>