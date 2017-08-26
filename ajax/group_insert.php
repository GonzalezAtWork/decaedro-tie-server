<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$entId = $_REQUEST['entId'];
$groupName = $_REQUEST['groupName'];

/*
$entId = $_GET['entId'];
$groupName = $_GET['groupName'];
*/

include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = "insert into groups (id_perfil, group_name) values (".$entId.",'".$groupName."');";

/*
$myFile = "group.sql";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $query."\n");
fclose($fh);
*/

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);
?>