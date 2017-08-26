<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_ocorrencia = $_REQUEST['id_ocorrencia'];
$id_equipe = $_REQUEST['id_equipe'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = " update ocorrencias set ";
if($id_equipe != ""){
	$query .= " id_equipe = " . $id_equipe . " ";
}else{
	$query .= " id_equipe = null ";
}
$query .= " where id_ocorrencia = " . $id_ocorrencia. ";";

//echo $query;

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
echo json_encode($dados[0]);
?>