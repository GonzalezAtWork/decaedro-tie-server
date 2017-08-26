<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_interferencia = $_REQUEST['id_interferencia'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

//$query  = "delete from pontosTipo where (id_interferencia = ".$id_interferencia.") ";
$query  = "update interferencias set ativo = FALSE where (id_interferencia = ".$id_interferencia.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>