<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_vistoria = $_REQUEST['id_vistoria'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "delete from vistorias where (id_vistoria = ".$id_vistoria.") ";
//$query  = "update vistorias set ativo = FALSE where (id_vistoria = ".$id_vistoria.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>