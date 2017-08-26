<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$simak = $_REQUEST['simak'];
$semana = $_REQUEST['semana'];
$face = $_REQUEST['face'];
$nome_imagem = $_REQUEST['nome_imagem'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "insert into publicidadeVeiculacao (simak, semana, face, nome_imagem) values ";
$query .= "('".$simak."',".$semana.",'".$face."','".$nome_imagem."');";

$db->setQuery($query);

try {

	$db->execute();
	$result = $db->getResultSet();
	#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
	echo json_encode($result[0]);

} catch(Exception $e) {

	return $query;

}

?>