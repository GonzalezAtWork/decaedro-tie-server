<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

function exceptionHandler($exception) {

	if (strpos($exception->getMessage(), 'duplicar valor da chave') > 0) {
		echo '<h1 style="color:#005599;height:140px;line-height:150px;">O nome da imagem já existe no banco de dados.</h1>';
	} else {
		echo "<div class='error'".$exception->getMessage()."</div>";
	}

}

//Seta função de tratamento de exceção
set_exception_handler('exceptionHandler');


$periodo_inicio = $_REQUEST['periodo_inicio'];
$periodo_fim = $_REQUEST['periodo_fim'];
$nome = str_replace("c:\\fakepath\\", "", strtolower(substr($_REQUEST['nome'], 0, strpos($_REQUEST['nome'], "."))));
$observacao = $_REQUEST['observacao'];
$imagem = substr($_REQUEST['imagem'], 23);

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query  = "insert into publicidadeimagens (periodo_inicio, periodo_fim, nome, observacao, imagem) values ";
$query .= "('".$periodo_inicio."','".$periodo_fim."','".$nome."','".$observacao."','".$imagem."');";

$db->setQuery($query);
$db->execute();
$result = $db->getResultSet();

#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
echo json_encode($result[0]);
?>