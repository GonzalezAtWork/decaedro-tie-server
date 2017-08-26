<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_item = $_REQUEST['id_item'];
$sigla = $_REQUEST['sigla'];
$nome = $_REQUEST['nome'];

$codigo = $_REQUEST['codigo'];
$foto = $_REQUEST['foto'];
$critico = $_REQUEST['critico'];
$chuva = $_REQUEST['chuva'];
$urgente = $_REQUEST['urgente'];
$cotia = $_REQUEST['cotia'];
$eletrica = $_REQUEST['eletrica'];
$cobertura_maior = $_REQUEST['cobertura_maior'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = " update vistoriasItens set ";
$query .= " sigla = '" . $sigla ."', ";
$query .= " nome = '" . $nome ."', ";
$query .= " codigo = '" . $codigo ."', ";
$query .= " foto = " . $foto .", ";
$query .= " critico = " . $critico .", ";
$query .= " chuva = " . $chuva .", ";
$query .= " urgente = " . $urgente .", ";
$query .= " cotia = " . $cotia .", ";
$query .= " eletrica = " . $eletrica .", ";
$query .= " cobertura_maior = " . $cobertura_maior ." ";
$query .= " where id_item = " . $id_item . ";";

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>