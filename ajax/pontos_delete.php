<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_ponto = $_REQUEST['id_ponto'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from pontos where (id_ponto = ".$id_ponto.") ";
$query  = "update pontos set ativo = FALSE where (id_ponto = ".$id_ponto.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>