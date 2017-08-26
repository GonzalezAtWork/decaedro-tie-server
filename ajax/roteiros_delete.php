<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_roteiro = $_REQUEST['id_roteiro'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from roteiros where (id_roteiro = ".$id_roteiro.") ";
$query  = "update roteiros set ativo = FALSE where (id_roteiro = ".$id_roteiro.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>