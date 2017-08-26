<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

function geraSenha($tamanho = 8, $numeros = true, $maiusculas = false, $simbolos = false) {

	// Caracteres de cada tipo
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';

	// Variáveis internas
	$retorno = '';
	$caracteres = '';

	// Agrupamos todos os caracteres que poderão ser utilizados
	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;

	// Calculamos o total de caracteres possíveis
	$len = strlen($caracteres);

	for ($n = 1; $n <= $tamanho; $n++) {
		// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
		$rand = mt_rand(1, $len);
		// Concatenamos um dos caracteres na variável $retorno
		$retorno .= $caracteres[$rand-1];
	}

	return $retorno;
}

$cpf = $_REQUEST['cpf'];

$senha = geraSenha();

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query = "SELECT nome, email from usuarios where cpf = '".$cpf."'";

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

$to = $dados[0]['email']; // Email de destino

$subject = "Envio de nova senha";

$body  = "Olá, ".$dados[0]['nome'].",\n\n"; //Nome do usuário
$body .= "Conforme solicitado, estamos enviando a sua nova senha de acesso ao sistema.\n\n";
$body .= "Nova senha: ".$senha."\n\n";
$body .= "Muito obrigado,\n\nA administração\n";

$headers = "From: Kalitera <contato@kalitera.com.br>"."\r\n"."X-Mailer: php";

//Salvando log da query executada
/*
if ($db->debug) {
	$myFile = "mail_log.txt";
	$fh = fopen($myFile, 'a') or die("Não foi possível abrir arquivo de log.");
	fwrite($fh, $body."\n\n");
	fclose($fh);
}
*/
if (mail($to, $subject, $body, $headers)) {

	$query = "UPDATE usuarios SET senha = '".md5($senha)."' WHERE CPF = '".$cpf."';";
	$db->setQuery($query);
	$db->execute();
	echo "TRUE";
	
} else {
	echo "FALSE";
}
?>