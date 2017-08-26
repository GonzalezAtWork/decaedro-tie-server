<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

$id_status = $_REQUEST['id_status'];

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "update pontosStatus set ativo = FALSE where (id_status = ".$id_status.");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>