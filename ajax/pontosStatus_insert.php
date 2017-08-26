<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$nome = $_REQUEST['nome'];
$observacoes = $_REQUEST['observacoes'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "insert into pontosStatus(nome, observacoes, ativo) values ( '".$nome."', '".$observacoes."', true );";

$db->setQuery($query);
$db->execute();
$result = $db->getResultSet();

#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
echo json_encode($result[0]);
?>