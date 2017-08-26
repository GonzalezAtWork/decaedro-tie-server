<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

$simak = $_REQUEST['simak'];
//Conexão ao banco de dados
$db = Database::getInstance();
$query  = " ";
$query .= " select pontos.id_ponto, pontos.id_roteiro, pontos.endereco, pontos.codigo_abrigo, roteiros.nome as roteiro from pontos ";
$query .= " inner join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
$query .= " where codigo_abrigo = '". $simak ."' ";

$db->setQuery($query);
$db->execute();

$dados = $db->getResultSet();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
echo json_encode($dados);