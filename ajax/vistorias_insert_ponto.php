<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_vistoria = (isset($_REQUEST['id_vistoria']))?$_REQUEST['id_vistoria']:"";
$id_ponto = (isset($_REQUEST['id_ponto']))?$_REQUEST['id_ponto']:"";

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

if($id_ponto != ""){
	$query   = "";
	$query  .= " insert into ocorrencias (id_vistoria, id_ponto)  ";
	$query  .= " values ('". $id_vistoria ."', '". $id_ponto ."'); ";
}
$db->setQuery($query);
$db->execute();

////echo $query;
//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);
?>