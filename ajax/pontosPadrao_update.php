<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_padrao = $_REQUEST['id_padrao'];
$id_tipo = $_REQUEST['id_tipo'];
$nome = $_REQUEST['nome'];
$croquis = $_REQUEST['croquis'];
$foto = $_REQUEST['foto'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = " update pontosPadrao set ";
$query .= " nome = '" . $nome ."', ";
$query .= " croquis = '" . $croquis ."', ";
$query .= " foto = '" . $foto ."', ";
$query .= " id_tipo = " . $id_tipo ." ";
$query .= " where id_padrao = " . $id_padrao;

if ($_REQUEST['debug'] == 'OK'){
	//echo $query;
}

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>