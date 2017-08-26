<?php
ini_set("memory_limit", "256M");

ob_start("ob_gzhandler"); 
header('Access-Control-Allow-Origin: *');
header( 'Content-type: application/json; charset=UTF-8' );
header( 'Content-Encoding: gzip' );
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-Disposition: attachment; filename=geral.bkp" );
header("Content-Description: Kalitera Dump DataBase" );

include('../classes/XMLObject.php');
include('../classes/database.php');

$id_usuario = (isset($_REQUEST['id_usuario']))?$_REQUEST['id_usuario']:"";
if($id_usuario == ""){ $id_usuario = 0; }
$device = (isset($_REQUEST['device']))?$_REQUEST['device']:"";
if($device == ""){ $device = $_SERVER["REMOTE_ADDR"]; }

// Conexão ao banco de dados
$db = Database::getInstance();

$retorno = "";

// CRIACAO DE SEQUENCES
$query = " select sequence_name from information_schema.sequences where sequence_schema='public' ; ";
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();
foreach ($dados as $row) {
	$query  = " select ";
	$query .= "		'DROP SEQUENCE IF EXISTS ' || sequence_name || ' CASCADE; ' || ";
	$query .= "		'CREATE SEQUENCE ' || sequence_name || ";
	$query .= "		' INCREMENT ' || increment_by || ";
	$query .= "		'  MINVALUE ' || min_value || ";
	$query .= "		'  MAXVALUE ' || max_value || ";
	$query .= "		'  START ' || (last_value + 1) || ";
	$query .= "		'  CACHE 1 ; ' as linha ";
	$query .= " from " . $row["sequence_name"];
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	foreach ($dados as $row) {
		$retorno .=    $row["linha"] . chr(13) . chr(10);
	}
}

// CRIACAO DE TABLES
$query  = "";
$query .= " select ";
$query .= " 	'DROP TABLE IF EXISTS ' || table_name || '; ' || ";
$query .= " 	'CREATE TABLE IF NOT EXISTS ' || table_name || '(' || string_agg(fields, ', ') || ');'  as linha ";
$query .= " from ( ";
$query .= " 	select ";
$query .= " 		table_name, ";
$query .= " 		column_name || ' ' || ";
$query .= " 		data_type || ' ' || ";
$query .= " 		case when character_maximum_length is not null then '(' || character_maximum_length::varchar ||') ' else '' end || ' ' || ";
$query .= " 		case when is_nullable = 'YES' then ' NOT NULL ' else '' end || ' ' || ";
$query .= " 		case when column_default != '' then ' DEFAULT ' || column_default else '' end as fields ";
$query .= " 	from information_schema.columns where table_schema='public'  ";
$query .= " 	order by table_name, ordinal_position ";
$query .= " ) as tables ";
$query .= " group by table_name ; ";
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();
foreach ($dados as $row) {
	$retorno .=    $row["linha"] . chr(13) . chr(10);
}

// CRIACAO DE INSERTS

$query  = "";
$query .= "select table_name from information_schema.tables where table_schema='public';";
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();
foreach ($dados as $row) {
	$continua = true;
	if( $row["table_name"] == "fotografias" ){
		$continua = false;
	}
	if( $continua ){
		$query  = "";
		$query .= "select * from " . $row["table_name"];
		$db->setQuery($query);
		$db->execute();
		$insert = $db->getResultSet();
		$retorno .=    "insert into ". $row["table_name"] . " values ". chr(13) . chr(10);
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
			$retorno .=    $campos;
			$cont1++;
			if($tot1 == $cont1){
				$retorno .=    " );" . chr(13) . chr(10);
			}else{
				$retorno .=    " )," . chr(13) . chr(10);		
			}
		}
	}
}

// AUDITORIA
$query = "insert into auditoria(ip_address,id_usuario,acao,obs ) values('". $device ."','". $id_usuario ."','9','Executou o dump do banco');";
$db->setQuery($query);
$db->execute();

echo $retorno;

ob_end_flush(); 
?>