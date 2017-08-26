<?php
ini_set("memory_limit", "1024M");

ob_start("ob_gzhandler"); 
header('Access-Control-Allow-Origin: *');
//header('Content-Type: text/plain; charset=utf-8'); 
header( 'Content-type: application/json; charset=UTF-8' );
header( 'Content-Encoding: gzip' );
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-Disposition: attachment; filename=dump_fotos.bkp" );
header("Content-Description: Kalitera Dump Fotos" );

include('../classes/XMLObject.php');
include('../classes/database.php');

$id_usuario = (isset($_REQUEST['id_usuario']))?$_REQUEST['id_usuario']:"";
if($id_usuario == ""){ $id_usuario = 0; }
$device = (isset($_REQUEST['device']))?$_REQUEST['device']:"";
if($device == ""){ $device = $_SERVER["REMOTE_ADDR"]; }

$limit = (isset($_REQUEST['limit']))?' limit ' . $_REQUEST['limit']:"";
$offset = (isset($_REQUEST['offset']))?' offset ' . $_REQUEST['offset']:"";

// Conexão ao banco de dados
$db = Database::getInstance();

$retorno = "";

// CRIACAO DE INSERTS DE FOTOGRAFIAS
$query  = "";
$query .= "select * from fotografias ". $limit . $offset ;
$db->setQuery($query);
$db->execute();
$insert = $db->getResultSet();
$retorno .= "insert into fotografias values " . chr(13) . chr(10);
$tot1 = $db->getRows();
$cont1 = 0;

foreach ($insert as $linha) {
	$retorno .=    "( ";
	$campos = "";
	foreach ($linha as $campo){
		if( $campo != ""){
			$campos .= "'" . $campo . "'";
		}else{
			$campos .= " null ";
		}
		$campos .= ",";
	}
	$campos .= "#";
	$campos = str_replace(",#", "", $campos);
	$campos = str_replace(chr(13), "", $campos);
	$campos = str_replace(chr(10), "", $campos);
	$retorno .=    $campos;
	$cont1++;
	if($tot1 == $cont1){
		$retorno .=    " );" . chr(13) . chr(10);
	}else{
		$retorno .=    " )," . chr(13) . chr(10);		
	}

	ob_flush();
	flush();
	usleep(300000);
	set_time_limit(20);
}

// AUDITORIA
$query = "insert into auditoria(ip_address,id_usuario,acao,obs ) values('". $device ."','". $id_usuario ."','9','Executou o dump de fotografias');";
$db->setQuery($query);
$db->execute();

echo $retorno;

ob_end_flush(); 
?>