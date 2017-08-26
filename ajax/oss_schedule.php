<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_os = $_REQUEST['id_os'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexao ao banco de dados
$db = Database::getInstance();

$query  = " update oss set agendada = true where id_os = " . $id_os .";";

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>