<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_limite_terreno = $_REQUEST['id_limite_terreno'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from pontosTipo where (id_limite_terreno = ".$id_limite_terreno.") ";
$query  = "update limiteTerreno set ativo = FALSE where (id_limite_terreno = ".$id_limite_terreno.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>