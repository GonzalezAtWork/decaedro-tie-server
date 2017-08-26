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

$db = Database::getInstance();

$device = (isset($_REQUEST['device']))? anti_sql_injection($_REQUEST['device']):"";
$id_usuario = (isset($_REQUEST['id_usuario']))? anti_sql_injection($_REQUEST['id_usuario']):"";
$token = (isset($_REQUEST['token']))? anti_sql_injection($_REQUEST['token']):"";
$latitude = (isset($_REQUEST['latitude']))? anti_sql_injection($_REQUEST['latitude']):"";
$longitude = (isset($_REQUEST['longitude']))? anti_sql_injection($_REQUEST['longitude']):"";
$altitude = (isset($_REQUEST['altitude']))? anti_sql_injection($_REQUEST['altitude']):"";
$accuracy = (isset($_REQUEST['accuracy']))? anti_sql_injection($_REQUEST['accuracy']):"";
$velocidade = (isset($_REQUEST['velocidade']))? anti_sql_injection($_REQUEST['velocidade']):"";
$bearing = (isset($_REQUEST['bearing']))? anti_sql_injection($_REQUEST['bearing']):"";

if($device == ""){
	$device = $_SERVER["REMOTE_ADDR"];
}

if($id_usuario == "" && $token != ""){
	$query  = " select * from mobile_login where token = '". $token ."' ";
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	foreach ($dados as $row) {
		$id_usuario = $row["id_usuario"];
	}
}

$query  = "";
$query .= " insert into gps_logger (id_usuario, device, latitude, longitude, altitude, accuracy, velocidade, bearing) values (";
$query .= " ". $id_usuario .", ";
$query .= " '". $device ."', ";
$query .= " '". $latitude ."', ";
$query .= " '". $longitude ."', ";
$query .= " '". $altitude ."', ";
$query .= " '". $accuracy ."', ";
$query .= " '". $velocidade ."', ";
$query .= " '". $bearing ."'); ";

$query .= " select 'OK' as processado; ";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();
echo json_encode($dados[0]);
?>