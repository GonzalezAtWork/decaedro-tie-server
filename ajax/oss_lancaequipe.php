<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_os = $_REQUEST['id_os'];
$id_equipe = $_REQUEST['id_equipe'];
$id_roteiro = $_REQUEST['id_roteiro'];
$qtd = $_REQUEST['qtd'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$db->setQuery('select id_prioridade from oss where id_os = '. $id_os);
$db->execute();
$db_oss = $db->getResultAsObject();
$id_prioridade = $db_oss->id_prioridade;

$query  = " update ocorrencias set ";
$query .= " id_equipe = " . $id_equipe . ", ";
$query .= " id_os = " . $id_os . " ";
$query .= " where id_ocorrencia in (";
$query .= "		select ocorrencias.id_ocorrencia from ocorrencias ";
$query .= "		inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
$query .= "		inner join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
$query .= "		left join vistorias on vistorias.id_vistoria = ocorrencias.id_vistoria ";
$query .= "		where id_os is null ";	
$query .= "		and ocorrencias.executada = false ";	
$query .= "		and gerar_os = true ";	
$query .= "		and ( ( ocorrencias.id_vistoria is not null and vistorias.executada = true ) or ( ocorrencias.id_vistoria is null ) ) ";	
$query .= "		and ( ocorrencias.id_equipe is null or ocorrencias.id_equipe = ". $id_equipe ." )";	
$query .= "		and pontos.id_roteiro = " . $id_roteiro;	
if($id_prioridade == 3){
	$query .= " and ocorrencias.nomeimagenspublicidade is not null ";	
}else{
	$query .= " and ocorrencias.nomeimagenspublicidade is null ";		
}
$query .= "		order by ocorrencias.data ";
$query .= "		LIMIT " . $qtd;
$query .= " );";

//echo $query;

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
echo json_encode($dados[0]);
?>