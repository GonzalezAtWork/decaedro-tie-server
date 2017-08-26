<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

//Seta função de tratamento de exceção
set_exception_handler('exceptionHandler');

$id_servidor = $_REQUEST['id_servidor'];
$id_usuario = $_REQUEST['usuario'];
$perfil = $_REQUEST['perfil'];
$cpf = $_REQUEST['cpf'];
$nome = $_REQUEST['nome'];
$nome_completo = $_REQUEST['nome_completo'];
$email = $_REQUEST['email'];
$ddd = $_REQUEST['ddd'];
$celular = $_REQUEST['celular'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "update usuarios set ";
$query .= "id_servidor = ".$id_servidor.", ";
$query .= "id_perfil = ".$perfil.", ";
$query .= "cpf = '".$cpf."', ";
$query .= "nome = '".$nome."', ";
$query .= "nome_completo = '".$nome_completo."', ";
$query .= "email = '".$email."', ";
$query .= "ddd = '".$ddd ."', ";
$query .= "celular = '".$celular."' ";
$query .= "where id_usuario = ".$id_usuario.";";



#Salvando log da query executada
/*
if ($db->debug) {
	$myFile = "user_update.sql";
	$fh = fopen($myFile, 'a') or die("Não foi possível abrir arquivo de log.");
	fwrite($fh, $query."\n\n");
	fclose($fh);
}
*/
try {
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();

	#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
	echo json_encode($dados[0]);

} catch (Exception $e) {

	echo "Query: ".$query."<br>".$e->getMessage();

}
?>