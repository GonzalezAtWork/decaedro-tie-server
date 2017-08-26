<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$stamp = $_REQUEST['stamp'];
$nome = $_REQUEST['nome'];
$data = $_REQUEST['data'];
$id_ponto = $_REQUEST['id_ponto'];
$id_ocorrencia = $_REQUEST['id_ocorrencia'];
$id_os = $_REQUEST['id_os'];
$id_vistoria = $_REQUEST['id_vistoria'];
$id_item = $_REQUEST['id_item'];
$foto = $_REQUEST['foto'];

$query   = "insert into fotografias ( stamp, nome, data, id_ponto, id_ocorrencia, id_os, id_vistoria, id_item, base64 ) "; 
$query  .= "values ( ";
$query  .= "'". $stamp ."',";
$query  .= "'". $nome ."',";
$query  .= "'". $data ."',";
$query  .= "". $id_ponto .",";
$query  .= "". $id_ocorrencia .",";
$query  .= "". $id_os .",";
$query  .= "". $id_vistoria .",";
$query  .= "". $id_item .",";
$query  .= "'". $foto ."'";
$query  .= ");";

$db->setQuery($query);
$db->execute();
echo json_encode(null);

?>