<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_usuario = $_REQUEST['id_usuario'];
$id_status = $_REQUEST['id_status'];
$id_ponto = $_REQUEST['id_ponto'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = "insert into pontosStatusHistorico (id_ponto, id_usuario, id_status, id_os ) values ( ";
$query  .= " '".$id_ponto."', ";
$query  .= " '".$id_usuario."', ";
$query  .= " '".$id_status."', ";
$query  .= " 0 ";
$query  .= " );";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);
?>