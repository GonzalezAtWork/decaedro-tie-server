<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_bairro = $_REQUEST['id_bairro'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from bairros where (id_bairro = ".$id_bairro.") ";
$query  = "update bairros set ativo = FALSE where (id_bairro = ".$id_bairro.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>