<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_piso_calcada = $_REQUEST['id_piso_calcada'];
$nome = $_REQUEST['nome'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = " update pisoCalcada set ";
$query .= " nome = '" . $nome ."' ";
$query .= " where id_piso_calcada = " . $id_piso_calcada . ";";

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>