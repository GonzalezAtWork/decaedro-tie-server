<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8'); 
 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$token = (isset($_REQUEST['token']))?$_REQUEST['token']:"";
$fotos = (isset($_REQUEST['fotos']))?$_REQUEST['fotos']:"";
$id_usuario = (isset($_REQUEST['id_usuario']))?$_REQUEST['id_usuario']:"";
$device = $_SERVER["REMOTE_ADDR"];
$email_body = '';

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexao ao banco de dados
$db = Database::getInstance();

if($fotos != "" && $token != ""){
	$query  = " select * from mobile_login where token = '". $token ."' ";
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	foreach ($dados as $row) {
		$device = $row["device"];
		$id_usuario = $row["id_usuario"];
	}
}
if($id_usuario != ""){
	$query = "";
	$query = "insert into fotografias (base64, data, id_item, id_vistoria, id_os, id_ocorrencia, id_ponto, nome, stamp) values";

	$fotos = str_replace("[","",$fotos);
	$fotos = str_replace("]","",$fotos);
	$fotos = explode("},{",$fotos);
	foreach ($fotos as $foto) {
		$foto = str_replace("{","",$foto);
		$foto = str_replace("}","",$foto);
		// PHP NAO ACEITA QUEBRA DE LINHA NO JSON!!!
		$foto = str_replace("\n","",$foto);
		$foto = '{'. trim($foto) .'}';
		$foto = json_decode( $foto );

if($email_body == ''){

	$query  = " select * from ocorrencias ";
	$query .= " inner join ";
	$query .= " where id_ocorrencia = ". $foto->id_ocorrencia ." ";

	$db->setQuery($query);
	$db->execute();
	$ocorrencia = $db->getResultSet();

	$email_body .= '<!DOCTYPE html><html><head><meta http-equiv="content-language" content="pt-BR"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta http-equiv="cache-control" content="no-cache"/><meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT"/><meta http-equiv="pragma" content="no-cache"/><title>Kalitera - Nova Ocorrência</title><style>body {margin: 0px 0px 0px 0px;font-family: Verdana,Helvetica,sans-serif;font-size: 9pt;width: 100%;text-align: center;}fieldset {border: 3px solid rgb(0, 92, 149);padding: 6px 16px 16px 6px;text-align: left;margin: 16px 10px 10px 16px;background-color: rgb(244, 244, 255);border-radius: 16px 16px 16px 16px;width: 90%;}legend {font-family: Verdana,Helvetica,sans-serif;font-size: 9pt;font-weight: bold;background-color: rgb(0, 92, 149);color: rgb(255, 255, 255);padding: 8px 16px;border-radius: 16px 16px 16px 16px;}ul {list-style-type: none;margin: 0px 10px 0px 0px;background: none repeat scroll 0% 0% rgb(238, 238, 238);padding: 5px;width: 420px;}li {font-weight: normal;color: rgb(85, 85, 85);margin: 5px;padding: 5px;font-size: 1em;width: 400px;border: 1px solid rgb(211, 211, 211);background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAGQEAAAAAAao4lEAAAAAmJLR0T//xSrMc0AAAAJcEhZcwAAAEgAAABIAEbJaz4AAABISURBVDjLY3iXxzCKRhHV0bNnDM+NGJ7fYXgxk+FlJsOrOIbXIQxvYhjepjK8i2Z4H8DwwZjhIzPDx7UMn+QYPhmOolFEDAIAjUD2JAAuNW8AAAAldEVYdGRhdGU6Y3JlYXRlADIwMTMtMDMtMTRUMTI6MTc6MjctMDc6MDChzYExAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDEzLTAzLTE0VDEyOjE3OjI3LTA3OjAw0JA5jQAAAABJRU5ErkJggg==) repeat-x scroll 50% 50% rgb(230, 230, 230);}</style></head>';
	$email_body .= '<body align="center">';
	$email_body .= '	<fieldset>';
	$email_body .= '		<legend>Dados da Ocorrência - Código 15055</legend>';
	$email_body .= '		<div width="100%" align="center">';
	$email_body .= '			<ul>';
	$email_body .= '				<li align="left"><B>DATA: </B>2013-09-24 19:09:02.321225</li>';
	$email_body .= '				<li align="left"><B>USUÁRIO: </B>VALTER ARAUJO (Super Usuário)</li>';
	$email_body .= '				<li align="left"><B>SIMAK: </B>7453</li>';
	$email_body .= '				<li align="left"><B>OTIMA: </B>0106180101006</li>';
	$email_body .= '				<li align="left"><B>ENDEREÇO: </B>VEREADOR JOAO DE LUCA, 1601</li>';
	$email_body .= '				<li align="left"><B>ROTEIRO: </B>PINK</li>';
	$email_body .= '				<li align="left"><B>TIPO: </B>NÃO CONFORMIDADE</li>';
	$email_body .= '			</ul>';
	$email_body .= '		</div>';
	$email_body .= '	</fieldset>';
	$email_body .= '	<fieldset>';
	$email_body .= '		<legend>Observações</legend>';
	$email_body .= '		<div width="100%" align="center">';
	$email_body .= '			Aqui vai um teste do que seria escrito em observações';
	$email_body .= '		</div>';
	$email_body .= '	</fieldset>';
	$email_body .= '	<fieldset>';
	$email_body .= '		<legend>Itens Vistoriados</legend>';
	$email_body .= '		<div width="100%" align="center">';
	$email_body .= '			<ul>';
	$email_body .= '				<li align="center">VIDRO POSTERIOR PICHADO</li>';
	$email_body .= '				<li align="center">VIDRO POSTERIOR QUEBRADO</li>';
	$email_body .= '			</ul>';
	$email_body .= '		</div>';
	$email_body .= '	</fieldset>';
	$email_body .= '	<fieldset>';
	$email_body .= '		<legend>Fotografias</legend>';
	$email_body .= '		<div align="center">';
}
$email_body .= '<img download="'. $foto->nome .'.jpg" src="data:image/png;base64,'. $foto->base64 .'" height="500"><br>&nbsp;<br>&nbsp;';

		$query .= "(";
		$query .= "'". $foto->base64 ."',";
		$query .= "'". $foto->data ."',";
		$query .= "'". $foto->id_item ."',";
		$query .= "'". $foto->id_vistoria ."',";
		$query .= "'". $foto->id_os ."',";
		$query .= "'". $foto->id_ocorrencia ."',";
		$query .= "'". $foto->id_ponto ."',";
		$query .= "'". $foto->nome ."',";
		$query .= "'". $foto->stamp ."'";
		$query .= "),";
	}
	$query .= ";";
	$query = str_replace(",;",";",$query);

$email_body .= '		</div>		';
$email_body .= '	</fieldset>';
$email_body .= '</body>';
$email_body .= '</html>';


	try{
		$db->setQuery($query);
		$db->execute();
		echo '{"postgresql":"OK"}';

	}catch(Exception $e){
		echo '{"ERROR":"'. $e->getMessage() .'"}';
	}
}else{
	echo '{"ERROR":"Usuário não encontrado"}';
}
?>
