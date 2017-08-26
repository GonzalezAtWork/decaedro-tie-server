<?php
header("Access-Control-Allow-Origin: *");

function anti_sql_injection($str) {
	if (!is_numeric($str)) {
		$str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
		//$str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
	}
	return $str;
}

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
date_default_timezone_set('America/Sao_Paulo');

$cpf = $_REQUEST['cpf'];
$senha = $_REQUEST['senha'];
$device = (isset($_REQUEST['device']))?$_REQUEST['device']:$_SERVER["REMOTE_ADDR"];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

try {

	$query  = "select s.url, u.id_usuario, u.nome, u.id_perfil, p.nome as nome_perfil ";
	$query .= "from usuarios u ";
	$query .= "inner join perfis p on p.id_perfil = u.id_perfil ";
	$query .= "inner join servidores s on s.id_servidor = u.id_servidor ";
	$query .= "where u.cpf = '".anti_sql_injection($cpf)."' and u.senha = '".anti_sql_injection($senha)."';";

	$db->setQuery($query);
	$db->execute();

	$login = $db->getResultAsObject();

	#Se não tem perfil, significa que usuário ou senha estão errados

	if ($db->getRows() > 0) {

		try {

			#Buscando permissões
			$query  = "select perm.token ";
			$query .= "from permissoes as perm, perfil_permissoes as aux ";
			$query .= "where perm.id_permissao = aux.id_permissao ";
			$query .= "and aux.id_perfil = ".$login->id_perfil;

			$db->setQuery($query);
			$db->execute();

			$dados = $db->getResultSet();

			#Concatena permissões em string e pega o nome do grupo
			$permissoes = '.';
			foreach ($dados as $row) {
				$permissoes .= strtolower($row['token']).".";
			}

			$result = array(
				"url_webservice"=>$login->url,
				"id_usuario"=>$login->id_usuario,
				"nome_usuario"=>$login->nome,
				"id_perfil"=>$login->id_perfil,
				"nome_perfil"=>$login->nome_perfil,
				"permissoes"=>$permissoes
			);

			#Gravando Log de login para auditoria
			if ($db->getRows() > 0) {

				try {

					$query = "insert into auditoria(ip_address, id_usuario, acao, obs) values('".$device."','". $login->id_usuario."','0','Logou no Sistema');";
					$db->setQuery($query);
					$db->execute();

					#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
					echo json_encode($result);

				} catch (Exception $e) {

					echo "Bloco 1 - ".$e->getMessage();

				}

			}


		} catch (Exception $e) {

			echo "Bloco 2 - ".$e->getMessage();

		}

	} else {

		#Usuário ou senha incorretos
		echo json_encode(NULL);
	}

} catch (Exception $e) {

	echo "Bloco 4 - ".$e->getMessage();
}

?>