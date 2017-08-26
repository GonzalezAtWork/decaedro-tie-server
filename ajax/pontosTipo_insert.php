<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$nome = $_REQUEST['nome'];
//echo "blevers:" . $_REQUEST['totem'];
if ($_REQUEST['totem'] == 'TRUE') {
	$totem = 'TRUE';
} else {
	$totem = 'FALSE';
}

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = "insert into pontosTipo(nome, totem) values ( '".$nome."' , ". $totem .");";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);
?>