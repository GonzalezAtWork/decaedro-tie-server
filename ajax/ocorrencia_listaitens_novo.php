<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8'); 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

$id_tipoitem = (isset($_REQUEST['id_tipoitem']))?$_REQUEST['id_tipoitem']:"0";

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "";
$query .= " select id_item, nome from vistoriasitens where id_tipoitem = " . $id_tipoitem . " order by nome ";
$db->setQuery($query);
$db->execute();
$itens = $db->getResultSet();

echo json_encode($itens);
?>