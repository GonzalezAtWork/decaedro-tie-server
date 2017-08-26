<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$entId = $_REQUEST['entId'];
$groupsToDelete = $_REQUEST['groupsToDelete'];

/*
$entId = $_GET['entId'];
$groupsToDelete = $_GET['groupsToDelete'];
*/

include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();


//Esta query começa com where false para que não seja preciso recortar a concatenação do operador OR
$query  = "delete from groups where false ";

foreach ($groupsToDelete as $value) {
    $query .= "or (id_perfil = ".$entId." and group_id = ".$value.")";
}

$query .= ";";

/*
$myFile = "query.sql";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $query."\n");
fclose($fh);
*/

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessario
echo json_encode($dados[0]);
?>