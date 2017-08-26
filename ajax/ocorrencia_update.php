<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8'); 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_ocorrencia = $_REQUEST['id_ocorrencia'];
$executada = $_REQUEST['executada'];
$vistoriada = $_REQUEST['vistoriada'];
$observacao = $_REQUEST['observacao'];
$id_equipe = $_REQUEST['id_equipe'];
$itensVistoria = $_REQUEST['itensVistoria'];
$observacaoVistoria = $_REQUEST['observacaoVistoria'];
$itensManutencao = $_REQUEST['itensManutencao'];
$observacaoManutencao = $_REQUEST['observacaoManutencao'];

if(is_array($itensVistoria)){
	$itensVistoria = implode(",", $itensVistoria);
}
$itensVistoria = str_replace(" ","", $itensVistoria);
$itensVistoria = str_replace(",",", ",$itensVistoria);

if(is_array($itensManutencao)){
	$itensManutencao = implode(",", $itensManutencao);
}
$itensManutencao = str_replace(" ","",$itensManutencao );
$itensManutencao = str_replace(",",", ",$itensManutencao);



include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexao ao banco de dados
$db = Database::getInstance();

$query  = "";
$query  .= " update ocorrencias set ";
$query  .= " vistoriada = " . $vistoriada . ", ";
$query  .= " executada = " . $executada . ", ";
$query  .= " observacao = '" . $observacao . "',";
if($id_equipe != ""){
	$query  .= " id_equipe = " . $id_equipe . ", ";
}
$query  .= " itensVistoria = '" . $itensVistoria . "',";
$query  .= " observacaoVistoria = '" . $observacaoVistoria . "',";
$query  .= " itensManutencao = '" . $itensManutencao . "',";
$query  .= " observacaoManutencao = '" . $observacaoManutencao . "' ";
$query  .= " where id_ocorrencia = " . $id_ocorrencia . ";";
//echo $query;

$db->setQuery($query);
$db->execute();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>