<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_interferencia = $_REQUEST['id_interferencia'];
$nome = $_REQUEST['nome'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = " update interferencias set ";
$query .= " nome = '" . $nome ."' ";
$query .= " where id_interferencia = " . $id_interferencia . ";";

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>