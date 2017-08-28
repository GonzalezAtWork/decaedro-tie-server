<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8'); 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

function anti_sql_injection($str) {
	if (!is_numeric($str)) {
		$str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
		//$str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
	}
	return $str;
}

include('../classes/XMLObject.php');
include('../classes/database.php');

$codigo = (isset($_REQUEST['codigo']))? anti_sql_injection($_REQUEST['codigo']):"";

$query  = "select id_ponto, endereco, codigo_abrigo as simak, codigo_novo as otima, gmaps_latitude, gmaps_longitude, pontosTipo.nome as tipo from pontos ";
$query .= " left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao  ";
$query .= " left join pontosTipo on pontosPadrao.id_tipo = pontosTipo.id_tipo  ";
$query .= " where id_ponto = ". $codigo ." or codigo_novo = '". $codigo ."' or codigo_abrigo = '". $codigo ."';";

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();
echo json_encode($dados[0]);
?>