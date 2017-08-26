<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$state = $_REQUEST['state'];
//$state = $_GET['state'];

include('../classes/database.php');

//Conexão ao banco de dados
$db = Database::getInstance();

$query = "select code, label from get_dropbox_cities('".$state."');";

$db->setQuery($query);

$db->execute();

//Retornando opções
echo json_encode($db->getResultSet());