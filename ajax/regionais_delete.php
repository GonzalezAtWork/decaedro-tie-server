<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_regional = $_REQUEST['id_regional'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from regionais where (id_regional = ".$id_regional.") ";
$query  = "update regionais set ativo = FALSE where (id_regional = ".$id_regional.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>