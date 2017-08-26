<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_tipo = $_REQUEST['id_tipo'];
$nome = $_REQUEST['nome'];
$cor = $_REQUEST['cor'];
$totem = $_REQUEST['totem'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = " update pontosTipo set ";
$query .= " nome = '" . $nome ."', ";
$query .= " cor = '" . $cor ."', ";
$query .= " totem = " . $totem ." ";
$query .= " where id_tipo = " . $id_tipo;

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