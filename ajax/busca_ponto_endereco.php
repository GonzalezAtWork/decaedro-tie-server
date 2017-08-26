<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8'); 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

function anti_sql_injection($str) {
	$str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
	return $str;
}

include('../classes/XMLObject.php');
include('../classes/database.php');

$endereco = isset($_REQUEST['endereco']))? anti_sql_injection($_REQUEST['endereco']):"";
$endereco = strtoupper($endereço);

$query  = "select id_ponto, endereco, codigo_abrigo as simak, codigo_novo as otima, gmaps_latitude, gmaps_longitude, pontosTipo.nome as tipo from pontos ";
$query .= " inner join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao  ";
$query .= " inner join pontosTipo on pontosPadrao.id_tipo = pontosTipo.id_tipo  ";
$query .= " where endereco like '%".$endereco."%';";

$db = Database::getInstance();
$db->setQuery($query. ";");
$db->execute();
$dados = $db->getResultSet();
echo json_encode($dados[0]);
?>