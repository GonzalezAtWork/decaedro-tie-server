<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_equipe = $_REQUEST['id_equipe'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from pontosTipo where (id_equipe = ".$id_equipe.") ";
$query  = "update equipes set ativo = FALSE where (id_equipe = ".$id_equipe.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>