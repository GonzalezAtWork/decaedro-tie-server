<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$id_servidor = $_REQUEST['id_servidor'];
$perfil = $_REQUEST['perfil'];
$cpf = $_REQUEST['cpf'];
$nome = $_REQUEST['nome'];
$nome_completo = $_REQUEST['nome_completo'];
$email = $_REQUEST['email'];
$ddd = $_REQUEST['ddd'];
$celular = $_REQUEST['celular'];

$query   = "insert into usuarios "; 
$query  .= "values ( ";
$query  .= "nextval('seq_usuario'), "; 				//Próximo índice
$query  .= "'".$perfil."', ";								//Perfil
$query  .= "'".$cpf."', ";									//CPF
$query  .= "'".$nome."', ";								//Nome do usuário
$query  .= "'202cb962ac59075b964b07152d234b70', "; //Senha - "123" em MD5
$query  .= "'".$email."', ";								//E-mail
$query  .= "'".$ddd."', ";									//DDD
$query  .= "'".$celular."', ";							//Celular
$query  .= "FALSE, ";										//logado_mobile
$query  .= "TRUE, ";											//Usuário ativo
$query  .= $id_servidor.",";                       //Servidor
$query  .= "'".$nome_completo."');";					//Nome completo do usuário

#Salvando log da query executada
/*
if ($db->debug) {
	$myFile = "query_log.sql";
	$fh = fopen($myFile, 'a') or die("Não foi possível abrir arquivo de log.");
	fwrite($fh, $query."\n\n");
	fclose($fh);
}
*/

$db->setQuery($query);
$db->execute();
echo json_encode(true);

?>