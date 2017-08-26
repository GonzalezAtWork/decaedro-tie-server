<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8'); 
 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$msg_erro = "";

$id_ponto = $_REQUEST['id_ponto'];

$id_usuario = (isset($_REQUEST['id_usuario']))?$_REQUEST['id_usuario']:"";
$token = (isset($_REQUEST['token']))?$_REQUEST['token']:"";

$executada = (isset($_REQUEST['executada']))?$_REQUEST['executada']:"false";
$observacao = (isset($_REQUEST['observacao']))?$_REQUEST['observacao']:"";
$id_equipe = (isset($_REQUEST['id_equipe']))?$_REQUEST['id_equipe']:"";
$itensVistoria = (isset($_REQUEST['itensVistoria']))?$_REQUEST['itensVistoria']:"";
$observacaoVistoria = (isset($_REQUEST['observacaoVistoria']))?$_REQUEST['observacaoVistoria']:"";
$itensManutencao = (isset($_REQUEST['itensManutencao']))?$_REQUEST['itensManutencao']:"";
$observacaoManutencao = (isset($_REQUEST['observacaoManutencao']))?$_REQUEST['observacaoManutencao']:"";
$id_vistoria = (isset($_REQUEST['id_vistoria']))?$_REQUEST['id_vistoria']:"";
$device = $_SERVER["REMOTE_ADDR"];

$fotos = (isset($_REQUEST['fotos']))?$_REQUEST['fotos']:"";

$retorno = "";

if (is_array($itensVistoria)) {
	$itensVistoria = implode(",", $itensVistoria);
}
$itensVistoria = str_replace(" ","", $itensVistoria);
$itensVistoria = str_replace(",",", ",$itensVistoria);

if (is_array($itensManutencao)) {
	$itensManutencao = implode(",", $itensManutencao);
}
$itensManutencao = str_replace(" ","",$itensManutencao );
$itensManutencao = str_replace(",",", ",$itensManutencao);


if ($id_equipe == "") {
	$id_equipe = 'null';
}
include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexao ao banco de dados
$db = Database::getInstance("sistema.kalitera.com.br");

if ($id_usuario == "" && $token != "") {
	$query  = " select * from mobile_login where token = '". $token ."' ";
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	foreach ($dados as $row) {
		$device = $row["device"];
		$id_usuario = $row["id_usuario"];
	}
}

if ($id_usuario != "") {
	$query  = "";
	$query  .= " insert into ocorrencias (id_ponto, gerar_os, executada, vistoriada, observacao, id_equipe, itensVistoria, observacaoVistoria, itensManutencao, observacaoManutencao, id_usuario ) values ( ";
	$query  .= " " . $id_ponto . ", ";
	$query  .= " true, ";
	$query  .= " " . $executada . ", ";
	$query  .= " true, ";
	$query  .= " '" . $observacao . "',";
	$query  .= " " . $id_equipe . ", ";
	$query  .= " '" . $itensVistoria . "',";
	$query  .= " '" . $observacaoVistoria . "',";
	$query  .= " '" . $itensManutencao . "',";
	$query  .= " '" . $observacaoManutencao . "', ";
	$query  .= " " . $id_usuario . " ";
	$query  .= " ) returning id_ocorrencia;";
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();

	$query = "";
	$query .= " insert into auditoria(ip_address,id_usuario,acao,obs )";
	$query .= "  values(";
	$query .= " '". $device ."',";
	$query .= " '". $id_usuario ."',";
	$query .= " '7',";
	$query .= " 'Nova Ocorrencia: <a href=\"home.php?action=ocorrencias_edit&id_ocorrencia=". $dados[0]["id_ocorrencia"] ."\">". $dados[0]["id_ocorrencia"] ."</a>'";
	$query .= " );";
	$db->setQuery($query);
	$db->execute();
 
	$retorno = json_encode($dados[0]);
	$id_ocorrencia = $dados[0]["id_ocorrencia"];

	$query  = "";
	$query .= " select ";
	$query .= "		ocorrencias.id_ocorrencia as id_ocorrencia, ";
	$query .= "		to_char(ocorrencias.data,'dd/MM/yyyy HH24:MI') as data_formatada, ";
	$query .= "		usuarios.nome as usuario, ";
	$query .= "		perfis.nome as perfil, ";
	$query .= "		pontos.endereco as endereco, ";
	$query .= "		pontos.codigo_abrigo as simak, ";
	$query .= "		pontos.codigo_novo as otima, ";
	$query .= "		roteiros.nome as roteiro, ";
	$query .= "		ocorrencias.observacao as observacao, ";
	$query .= "		ocorrencias.observacaovistoria as observacaovistoria, ";
	$query .= "		ocorrencias.observacaomanutencao as observacaomanutencao, ";
	$query .= "		ocorrencias.itensvistoria as itensvistoria ";
	$query .= " from ocorrencias ";
	$query .= " inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
	$query .= " inner join roteiros on roteiros.id_roteiro = pontos.id_roteiro ";
	$query .= " inner join usuarios on usuarios.id_usuario = ocorrencias.id_usuario ";
	$query .= " inner join perfis on perfis.id_perfil = usuarios.id_perfil ";
	$query .= " where id_ocorrencia = ". $id_ocorrencia;

	$db->setQuery($query);
	$db->execute();
	$dados_email = $db->getResultAsObject();

	$subject = "Nova Ocorrencia - Simak: " . $dados_email->simak . " - Ocorrencia: " . $dados_email->id_ocorrencia;

	$email_body  = '';
	$email_body .= '<!DOCTYPE html><html><head><meta http-equiv="content-language" content="pt-BR"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta http-equiv="cache-control" content="no-cache"/><meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT"/><meta http-equiv="pragma" content="no-cache"/><title>Kalitera - Nova Ocorrência</title><style>body {margin: 0px 0px 0px 0px;font-family: Verdana,Helvetica,sans-serif;font-size: 9pt;width: 100%;text-align: center;}fieldset {border: 3px solid rgb(0, 92, 149);padding: 6px 16px 16px 6px;text-align: left;margin: 16px 10px 10px 16px;background-color: rgb(244, 244, 255);border-radius: 16px 16px 16px 16px;width: 90%;}legend {font-family: Verdana,Helvetica,sans-serif;font-size: 9pt;font-weight: bold;background-color: rgb(0, 92, 149);color: rgb(255, 255, 255);padding: 8px 16px;border-radius: 16px 16px 16px 16px;}ul {list-style-type: none;margin: 0px 10px 0px 0px;background: none repeat scroll 0% 0% rgb(238, 238, 238);padding: 5px;width: 420px;}li {font-weight: normal;color: rgb(85, 85, 85);margin: 5px;padding: 5px;font-size: 1em;width: 400px;border: 1px solid rgb(211, 211, 211);background: repeat-x scroll 50% 50% rgb(230, 230, 230);}</style></head>';
	$email_body .= '<body align="center">';
	$email_body .= '<fieldset>';
	$email_body .= '<legend>Dados da Ocorrência - Código '. $dados_email->id_ocorrencia .'</legend>';
	$email_body .= '<div width="100%" align="center">';
	$email_body .= '<ul>';
	$email_body .= '<li align="left"><B>DATA: </B>'. $dados_email->data_formatada .'</li>';
	$email_body .= '<li align="left"><B>USUÁRIO: </B>'. $dados_email->usuario .' ('. $dados_email->perfil .')</li>';
	$email_body .= '<li align="left"><B>SIMAK: </B>'. $dados_email->simak .'</li>';
	$email_body .= '<li align="left"><B>OTIMA: </B>'. $dados_email->otima .'</li>';
	$email_body .= '<li align="left"><B>ENDEREÇO: </B>'. $dados_email->endereco .'</li>';
	$email_body .= '<li align="left"><B>ROTEIRO: </B>'. $dados_email->roteiro .'</li>';
	$email_body .= '</ul>';
	$email_body .= '</div>';
	$email_body .= '</fieldset>';
	$email_body .= '<fieldset>';
	$email_body .= '<legend>Observações</legend>';
	$email_body .= '<div width="100%" align="center">'. $dados_email->observacao .'</div>';
	$email_body .= '<div width="100%" align="center">'. $dados_email->observacaovistoria .'</div>';
	$email_body .= '<div width="100%" align="center">'. $dados_email->observacaomanutencao .'</div>';
	$email_body .= '</fieldset>';
	
	if ( $dados_email->itensvistoria  != "" ) {
		$email_body .= '<fieldset>';
		$email_body .= '<legend>Itens Vistoriados</legend>';
		$email_body .= '<div width="100%" align="center">';
		$email_body .= '<ul>';
		$db->setQuery(" select nome from vistoriasitens where id_item in (". $dados_email->itensvistoria .") ");
		$db->execute();
		$result = $db->getResultSet();
		foreach ($result as $row) {
			$email_body .= '<li align="center">'. $row["nome"] .'</li>';
		}
		$email_body .= '</ul>';
		$email_body .= '</div>';
		$email_body .= '</fieldset>';
	}

	$email_body .= '</body>';
	$email_body .= '</html>';

	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

	$headers = "MIME-Version: 1.1\r\n";
	//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Content-Type: multipart/mixed; \r\n"; 
	$headers .= "       boundary=\"{$mime_boundary}\" \r\n"; 
	$headers .= "From: Kalitera <contato@kalitera.com.br>\r\n";
	$headers .= "Return-Path: Kalitera <contato@kalitera.com.br>\r\n";

	$images = '';

if ($fotos != "") {
	
	$fotos = str_replace("NOVA", $id_ocorrencia, $fotos);
	$fotos = str_replace("[","",$fotos);
	$fotos = str_replace("]","",$fotos);
	$fotos = explode("},{",$fotos);

	$query = "insert into fotografias (base64, data, id_item, id_vistoria, id_os, id_ocorrencia, id_ponto, nome, stamp) values";

	foreach ($fotos as $foto) {
		$foto = str_replace("{","",$foto);
		$foto = str_replace("}","",$foto);
		// PHP NAO ACEITA QUEBRA DE LINHA NO JSON!!!
		$foto = str_replace("\n","###",$foto);
		$foto = '{'. trim($foto) .'}';
		$foto = json_decode( $foto );

		$images .= "\r\n\r\n--{$mime_boundary}\r\n";
		$images .= "Content-Type: image/jpeg; name=". $foto->nome .".jpg \r\n";
		$images .= "Content-Disposition: attachment; filename=". $foto->nome .".jpg \r\n";
		$images .= "Content-Transfer-Encoding: base64 \r\n";
		$images .= "X-Attachment-Id: ". $foto->nome ." \r\n\r\n";
		$images .= str_replace("###","\n",$foto->base64);

		$query .= "(";
		$query .= "'". str_replace("###","",$foto->base64) ."',";
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

	try {
		$db->setQuery($query);
		$db->execute();

	} catch(Exception $e) {
		$msg_erro = '{"ERROR":"'. $e->getMessage() .'"}';
	}
}

	$email_body = $email_body . $images;

	$str_destinatario = "";

	//$db->setQuery("select nome, email from usuarios where id_perfil = 0 or id_usuario = ". $id_usuario ." order by nome");
	$db->setQuery(" select nome, email from usuarios where id_perfil = 0 and ativo = 't' order by nome ");
	$db->execute();
	$destinatarios = $db->getResultSet();

	foreach ($destinatarios as $destinatario) {
		if ( $str_destinatario != "" ) {
			$str_destinatario .= ',';
		}
		$str_destinatario .= $destinatario["nome"].'<'.$destinatario["email"].'>';
	}

   //$headers .= 'Bcc:'.$str_destinatario . "\r\n";
	//$headers = $headers.$images;
	$headers .= "\r\n\r\n--{$mime_boundary}\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	$envio = mail($str_destinatario, $subject , $email_body, $headers);

	if ($envio) {
		//echo "Mensagem enviada com sucesso";
	} else {
		$msg_erro = '{"ERROR":"E-mail não enviado"}';
	}

} else {
	$msg_erro = '{"ERROR":"Usuário não encontrado"}';
}

if ($msg_erro == "") {
	//$msg_erro = '{"postgresql":"OK"}';
	$msg_erro = $retorno;
}

echo $msg_erro;
?>