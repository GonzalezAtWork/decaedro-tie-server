<?php
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();


$query  = " select 'OK' as postgresql ";
$db->setQuery($query);
$db->execute();

$dados = $db->getResultSet();

#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);