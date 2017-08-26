<?php
include('_showErrors.php');

$id_imagem = $_REQUEST['id_imagem'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "update publicidadeimagens set ativo = false ";
$query .= "where id_imagem = ".$id_imagem.";";

$db->setQuery($query);
$db->execute();
$result = $db->getResultSet();

#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
echo json_encode($result[0]);
?>