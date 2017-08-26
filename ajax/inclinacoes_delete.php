<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_inclinacao = $_REQUEST['id_inclinacao'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from inclinacoes where (id_inclinacao = ".$id_inclinacao.") ";
$query  = "update inclinacoes set ativo = FALSE where (id_inclinacao = ".$id_inclinacao.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>