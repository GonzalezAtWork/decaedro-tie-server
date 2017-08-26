<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$nome = $_REQUEST['nome'];
$lavagem = $_REQUEST['lavagem'];$usuarios = $_REQUEST['usuarios'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "insert into equipes(nome, lavagem) values ('".$nome."', '".$lavagem."') returning id_equipe; ";

#Salvando log da query executada
//if ($db->debug) {	$myFile = "query_log.sql";	$fh = fopen($myFile, 'a') or die("Não foi possível abrir arquivo de log.");	fwrite($fh, $query."\n\n");	fclose($fh);}
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();
$query = "";

foreach ($usuarios as $value) {	$query .= "insert into usuariosEquipes values(".$db_result->id_equipe.", ". $value ."); ";
}

$db->setQuery($query);$db->execute();echo TRUE;

?>