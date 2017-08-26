<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_perfil = $_REQUEST['id_perfil'];
$nome = $_REQUEST['nome'];
if (isset($_REQUEST['permissoes'])) {
	$permissoes = $_REQUEST['permissoes'];
}

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

#Atualizando o nome do perfil
$query  = " update perfis set ";
$query .= " nome = '" . $nome ."' ";
$query .= " where id_perfil = " . $id_perfil.";";


#Deletando as permissões anteriores
$query .= " delete from perfil_permissoes where id_perfil = " . $id_perfil.";";


#Se o array de permissões não está vazio
if (count($permissoes)) {
	#Inserindo as novas permissões
	$query .= " insert into perfil_permissoes (id_perfil, id_permissao) values ";
	foreach($permissoes as $value) {
		$query .= " (".$id_perfil.", ".$value."),";
	}
	$query = substr($query, 0, -1).";";
}


#Salvando log da query executada
/*
if ($db->debug) {
	$myFile = "perfil_log.sql";
	$fh = fopen($myFile, 'a') or die("Não foi possível abrir arquivo de log.");
	fwrite($fh, $query."\n\n");
	fclose($fh);
}
*/
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>