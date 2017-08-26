<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Consórcio</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	</head>
<body>
<?php

$id_usuario = (isset($_REQUEST['id_usuario']))?$_REQUEST['id_usuario']:"";
if($id_usuario == ""){ $id_usuario = 0; }
$device = (isset($_REQUEST['device']))?$_REQUEST['device']:"";
if($device == ""){ $device = $_SERVER["REMOTE_ADDR"]; }

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

	include('../classes/XMLObject.php');
	include('../classes/database.php');

	$tabelas_off = array( 
		"publicidadeimagens",
		"fotografias",
		"ocorrencias",
		"oss",
		"ossroteiros",
		"ossequipes",
		"gps_logger",
		"vistorias",
		"vistoriasroteiros",
		"vistoriasequipes",
		"publicidadeveiculacao",
		"auditoria"
	);

	$query = "insert into auditoria(ip_address,id_usuario,acao,obs ) values('". $device ."','". $id_usuario ."','9','Exportou dados para o Consórcio');";
	$db = Database::getInstance("sistema.kalitera.com.br");
	$db->setQuery($query);
	$db->execute();

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
$query  = "select table_name from information_schema.tables where table_schema='public';";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();
foreach ($dados as $row) {
	$continua = true;
	$key = in_array( $row["table_name"] , $tabelas_off );
	if( $key == true ){
		$continua = false;
	}
	if( $continua ){

		$query  = "";
		$query .= "select * from " . $row["table_name"];

		$db->setQuery($query);
		$db->execute();
		$insert = $db->getResultSet();
		if( $db->getRows() > 0 ){
			$retorno .=    "insert into ". $row["table_name"] . " values ". chr(13) . chr(10);
		}
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
$retorno = str_replace("NOT NULL","",$retorno);
$mensagem = "";
try{
	// EXECUÇÃO NO BANCO CONSORCIO
	$db_consorcio = Database::getInstance("consorcio.kalitera.com.br");
	$db_consorcio->setQuery( $retorno );
	$db_consorcio->execute();
	$mensagem = "Dump Executado";
} catch(Exception $e) {
	echo "<pre>".$retorno."</pre>";
	$mensagem = $e->getMessage();		
}
echo "<p>". $mensagem ."</p>";

$headers = "MIME-Version: 1.1\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$headers .= "From: Kalitera <sistema@kalitera.com.br>\r\n"; // remetente
$headers .= "Return-Path: Kalitera <sistema@kalitera.com.br>\r\n"; // return-path
$envio = mail("rogerio.gonzalez@gmail.com", "Dump Consorcio", $mensagem , $headers);

?>
</body>
</html>