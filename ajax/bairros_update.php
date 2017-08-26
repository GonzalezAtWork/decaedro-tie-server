<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_bairro = $_REQUEST['id_bairro'];
$nome = $_REQUEST['nome'];
$id_zona = $_REQUEST['id_zona'];
$vistoria = $_REQUEST['vistoria'];
$distancia = $_REQUEST['distancia'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = " update bairros set ";
$query .= " nome = '" . $nome ."', ";
$query .= " vistoria = '" . $vistoria ."', ";
$query .= " distancia = '" . $distancia ."', ";
$query .= " id_zona = '" . $id_zona ."' ";
$query .= " where id_bairro = " . $id_bairro;

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>