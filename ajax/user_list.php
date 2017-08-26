<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

//Conexão ao banco de dados
$db = Database::getInstance();

$query  = "select * from usuarios u ";
$query .= "where u.ativo = TRUE ";

/*
//Salvando log da query executada
if ($db->debug) {
	$myFile = "query_log.sql";
	$fh = fopen($myFile, 'a') or die("Não foi possível abrir arquivo de log.");
	fwrite($fh, $query."\n\n");
	fclose($fh);
}
*/

$db->setQuery($query);

if($_REQUEST['debug'] == 'OK'){
	//echo $query;
}

$db->execute();

$dados = $db->getResultSet();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
echo json_encode($dados);