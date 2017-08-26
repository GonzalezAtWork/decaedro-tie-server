<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_status = $_REQUEST['id_status'];
$nome = $_REQUEST['nome'];
$observacoes = $_REQUEST['observacoes'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = " update pontosStatus set ";
$query .= " nome = '" . $nome ."', ";
$query .= " observacoes = '" . $observacoes ."' ";
$query .= " where id_status = " . $id_status;

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>