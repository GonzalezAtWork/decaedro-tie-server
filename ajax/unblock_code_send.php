<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

/*
$entId = $_REQUEST['id_perfil'];
$email = $_REQUEST['email'];
*/

$entId = $_GET['id_perfil'];
$email = $_GET['email'];

include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query = "select u.id_usuario, u.user_name, e.ent_name from usuarios u, enterprises e where u.id_perfil = e.id_perfil and u.id_perfil = ".$entId." and u.user_email = '".$email."'";

$db->setQuery($query);

$db->execute();

$result = $db->getResultSet();

if (!is_array($result)) {

	echo "FALSE";

} else {

	$code = strtoupper(md5(date("Ymdhns")));
	
	#Como resultado é um array bidimensional, só precisamos do primeiro elemento
	$result = $result[0];
	
	$userId = $result['id_usuario'];
	$firstName = substr($result['user_name'], 0, strpos($result['user_name'].' ', ' '));
	$enterprise = $result['ent_name'];
	$to = $email;

	#Salvando código de desbloqueio
	$query = "update usuarios set user_unblock_code = '".$code."' where id_perfil = ".$entId." and id_usuario = ".$userId.";";
	
	$db->setQuery($query);
	$db->execute();
	
	#Concatenando corpo do e-mail
	$body = '<p>Olá '.$firstName.'</p>';
	$body .= '<p>Alguém - provavelmente você - solicitou instruções para a alteração de sua senha através do sistema de Planejamento de Recursos Empresariais da '.$enterprise.'.</p>';
	$body .= '<p>Se você deseja realmente modificar sua senha de acesso, por favor, clique no link abaixo ou digite o código de desbloqueio no campo correspondente no sistema.</p>';
	$body .= '<br><p><a href="http://localhost/sentec/index.php?unblock='.$code.'">Clique aqui</a> ou digite o seguinte código no sistema: '.$code.".</p><br>";
	$body .= '<p>Se você tiver qualquer dúvida, por favor, entre em contato com o suporte do sistema.</p>';
	$body .= '<p>Atenciosamente,</p>';
	$body .= '<p>'.$enterprise.'</p>';
	
	#Definindo assunto e cabeçalho
	$subject = 'Código de desbloqueio para a troca de senha';
	$headers = 'From: sistemas@sentec.com.br\r\nReply-To: sistemas@sentec.com.br\r\nX-Mailer: PHP/'.phpversion();

	#Enviando e-mail
	mail($to, $subject, $body, $headers);
	
	echo "TRUE";

}