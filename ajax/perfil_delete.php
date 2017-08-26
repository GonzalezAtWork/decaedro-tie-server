<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_perfil = $_REQUEST['id_perfil'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from perfis where (id_perfil = ".$id_perfil.") ";
$query  = "update perfis set ativo = FALSE where (id_perfil = ".$id_perfil.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>