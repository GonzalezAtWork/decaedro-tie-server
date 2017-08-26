<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_usuario = $_REQUEST['id_usuario'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

// Não deleta mais usuário, apenas inativa
//$query  = "delete from usuarios where (id_usuario = ".$id_usuario.");";

$query  = "update usuarios set ativo = FALSE where (id_usuario = ".$id_usuario.");";

//Salvando log da query executada
/*
if ($dbParameters->debug) {
	$myFile = "query_log.sql";
	$fh = fopen($myFile, 'a') or die("Não foi possível abrir arquivo de log.");
	fwrite($fh, $query."\n\n");
	fclose($fh);
}
*/

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>