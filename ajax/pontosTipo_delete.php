<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_tipo = $_REQUEST['id_tipo'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from pontosTipo where (id_tipo = ".$id_tipo.") ";
$query  = "update pontosTipo set ativo = FALSE where (id_tipo = ".$id_tipo.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>