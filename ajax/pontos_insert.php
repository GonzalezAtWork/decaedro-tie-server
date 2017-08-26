<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$endereco = $_REQUEST['endereco'];
$id_usuario = $_REQUEST['id_usuario'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = "insert into pontos(endereco) values ( '".$endereco."' ) returning id_ponto ";
$db->setQuery($query);
$db->execute();
$teste = $db->getResultAsObject();

$query  = "insert into pontosStatusHistorico values ( ". $teste->id_ponto .",1 ,0 ,".$id_usuario ." )";
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

////echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
//echo json_encode($dados[0]);
echo $teste->id_ponto;
?>