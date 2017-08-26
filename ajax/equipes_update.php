<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_equipe = $_REQUEST['id_equipe'];
$nome = $_REQUEST['nome'];
$lavagem = $_REQUEST['lavagem'];
$usuarios = $_REQUEST['usuarios'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "update equipes set ";
$query .= "nome = '" . $nome ."', ";
$query .= "lavagem = '" . $lavagem ."' ";
$query .= "where id_equipe = " . $id_equipe . "; ";

$query .= "delete from usuariosEquipes where id_equipe = " . $id_equipe. "; ";

foreach ($usuarios as $value) {
	$query .= " insert into usuariosEquipes values(". $id_equipe .", ". $value ."); ";
}

$db->setQuery($query);

$db->execute();
?>