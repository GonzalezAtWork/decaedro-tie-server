<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_os = $_REQUEST['id_os'];
$ocorrencias = $_REQUEST['ocorrencias'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexao ao banco de dados
$db = Database::getInstance();

$query  = "";
$query  .= " update ocorrencias set ";
$query  .= " id_os = null ";
$query  .= " where id_os = " . $id_os . ";";

$db->setQuery($query);
$db->execute();

if( $_REQUEST['ocorrencias'] != "" ){
	$query  = "";
	$query  .= " update ocorrencias set ";
	$query  .= " id_os = ". $id_os;
	$query  .= " where id_ocorrencia in (". implode(",", $ocorrencias) .");";

	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
}
/*
// Para limpar todas as equipes de ocorrencias que não estejam atreladas a uma OS
$query  = "";
$query  .= " update ocorrencias set ";
$query  .= " id_equipe = null ";
$query  .= " where id_os is null;";

$db->setQuery($query);
$db->execute();
*/

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>