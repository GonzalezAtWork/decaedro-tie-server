<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$userId = $_REQUEST['userId'];
$novasenha = $_REQUEST['novasenha'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query = "UPDATE usuarios SET senha = '".md5($novasenha)."' WHERE id_usuario = '". $userId ."';";
$query .= "SELECT nome, email from usuarios where id_usuario = '". $userId ."'";

////echo $query;
/*
if ($db->debug) {
	$myFile = "query.sql";
	$fh = fopen($myFile, 'w') or die("Não foi possível abrir arquivo de log.");
	fwrite($fh, $query."\n");
	fwrite($fh, "\n");
	fwrite($fh, implode(",", $_REQUEST)."\n");
	fclose($fh);
}
*/
$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();


$to = $dados[0]['email']; // Email de destino

$subject = "Alteração de senha";
$headers = "From: Kalitera <contato@kalitera.com.br>"."\r\n"."X-Mailer: php";

$body  = "Olá, ".$dados[0]['nome'].",\n\n"; //Nome do usuário
$body .= "Sua senha de acesso ao sistema foi alterada.\n\n";
$body .= "Nova senha: ".$novasenha."\n\n";
$body .= "Muito obrigado,\n\nA administração\n";

echo $body;

if (mail($to, $subject, $body, $headers)) {
	echo "TRUE";
} else {
	echo "FALSE";
}

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);
?>