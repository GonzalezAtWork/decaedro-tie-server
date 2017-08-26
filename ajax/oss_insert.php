<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$data = $_REQUEST['data'];
$id_gravidade = $_REQUEST['id_gravidade'];
$id_prioridade = $_REQUEST['id_prioridade'];
$chuva = $_REQUEST['chuva'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = "insert into oss(";
$query .= " data, id_gravidade, id_prioridade, chuva";
$query .= " ) values ( ";
$query .= "'".$data."',";
$query .= "'".$id_gravidade."',";
$query .= "'".$id_prioridade."',";
$query .= "'".$chuva."'";
$query .= " ) returning id_os;";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);
?>