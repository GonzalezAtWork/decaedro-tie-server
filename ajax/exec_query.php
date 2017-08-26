<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8'); 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

// O remetente deve ser um e-mail do seu domínio conforme determina a RFC 822.
// O return-path deve ser ser o mesmo e-mail do remetente.
$headers = "MIME-Version: 1.1\r\n";
//$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$headers .= "From: Kalitera <sistema@kalitera.com.br>\r\n"; // remetente
$headers .= "Return-Path: Kalitera <sistema@kalitera.com.br>\r\n"; // return-path

$executadas = "";
$querys = explode(";",  $_REQUEST["query"] );
foreach($querys as $query) {
	try {
		$executadas .= $query . ";<br/><br/>";
		$db->setQuery($query. ";");
		$db->execute();
	} catch (Exception $e) {
		$executadas .= 'ERROR: '. $e->getMessage() . '\n'. $query. ";<br/><br/>";
	}
}

$dados = $db->getResultSet();

$envio = mail("rogerio.gonzalez@gmail.com", "MobileExecQuery", $executadas, $headers);
 
if($envio){
	//echo "Mensagem enviada com sucesso";
}else{
	//echo "A mensagem não pode ser enviada";
}

echo json_encode($dados[0]);

?>