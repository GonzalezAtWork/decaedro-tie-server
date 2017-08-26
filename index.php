<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

#Inicia sessão
session_start();

if (isset($_SESSION['id_usuario'])) {
	unset($_SESSION['nome_usuario']);
	unset($_SESSION['id_perfil']);
	unset($_SESSION['nome_perfil']);
	unset($_SESSION['permissoes']);
}

include('classes/XMLObject.php');
$app = new XMLObject();
$app->loadXMLFromFile("config/application.xml");

#Inclui cabeçalho para index
include('includes/head.php');

#Se vem do link de senha esquecida
if (isSet($_REQUEST['lostpass'])) {

	#Pega o e-mail do usuário
	include('actions/sendpassword.php');

#Se não é nenhuma das opções acima, então é login
} else {

	#Inclui o fieldset com os campos de entrada do index
	include('actions/login.php');

}

#Inclui o rodapé para index
include("includes/tail.php");

?> 