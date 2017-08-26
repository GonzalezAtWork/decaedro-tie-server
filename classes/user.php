<?
/* --------------------------------------------------------------------------------------------------------- */

#Classe que controla os dados de usuário

class User {

	public $id_perfil = NULL,
			 $id_servidor = NULL,
			 $id_usuario = NULL,
			 $cpf = NULL,
			 $nome = NULL,
			 $senha = NULL,
			 $email = NULL,
			 $ddd = NULL,
			 $celular = NULL,
			 $nome_completo = NULL;

/* --------------------------------------------------------------------------------------------------------- */

	public function load($id_usuario = NULL) {
		
		#Se código de usuário não foi passado por parâmetro, pega da sessão
		if (is_null($id_usuario)) {
			$id_usuario = $_SESSION['id_usuario'];
		}

		#Define query a ser executada
		$query  = " select ";
		$query .= "    id_servidor, ";
		$query .= "    id_perfil, ";
		$query .= "    cpf, ";
		$query .= "    initcap(lower(nome)) as nome, ";
		$query .= "    initcap(lower(nome_completo)) as nome_completo, ";
		$query .= "    email, ";
		$query .= "    ddd, ";
		$query .= "    celular ";
		$query .= " from usuarios ";
		$query .= " where id_usuario = ".$id_usuario." and ativo = 't';";

		try {

			#Conecta na base de dados
			$db = Database::getInstance();
			$db->setQuery($query);
			$db->execute();

			#Pegando resultado da query como objeto
			$db_user = $db->getResultAsObject();

			#Atribuindo valores ao objeto
			$this->id_usuario = $id_usuario;
			$this->id_servidor = $db_user->id_servidor;
			$this->id_perfil = $db_user->id_perfil;
			$this->cpf = formatCPF($db_user->cpf);
			$this->nome = $db_user->nome;
			$this->nome_completo = $db_user->nome_completo;
			$this->email = $db_user->email;
			$this->ddd = $db_user->ddd;
			$this->celular = substr($db_user->celular, 0, 3)."-".substr($db_user->celular, 3, 3)."-".substr($db_user->celular, 6, 3);

		} catch (Exception $e) {
			die ("Erro:".$e->getMessage());
		}

	}

/* --------------------------------------------------------------------------------------------------------- */
	/*
	public function save($type = NULL) {

		#Conecta na base de dados
		$db = Database::getInstance();
		
		#identificando insert ou update
		if (is_null($type)) {
			$query = "set_user(";
		} else {
			$query = "new_user(";
		}		

		#Construindo chamada da função do postgre
		$query .= $this->id_servidor.",";
		$query .= $this->id_usuario.",";
		$query .= $this->id_perfil.",";
		$query .= $this->cpf.",";
		$query .= $this->nome.",";
		$query .= $this->email.",";
		$query .= $this->ddd.",";
		$query .= $this->celular.",";
		$query .= $this->nome_completo.");";

		$db->setQuery($query);
		$db->execute();

	}
	*/
/* --------------------------------------------------------------------------------------------------------- */

}
?>