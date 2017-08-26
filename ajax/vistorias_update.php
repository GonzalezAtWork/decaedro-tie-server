<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_vistoria = $_REQUEST['id_vistoria'];
$lista_pontos = $_REQUEST['lista_pontos'];

$data = $_REQUEST['data'];
$id_gravidade = $_REQUEST['id_gravidade'];
$periodo = $_REQUEST['periodo'];
$equipes = $_REQUEST['equipes'];


include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexao ao banco de dados
$db = Database::getInstance();

$query  = "";

$query  .= " update vistorias set ";
$query  .= " data = '". $data ."', ";
$query  .= " id_gravidade = '". $id_gravidade ."', ";
$query  .= " periodo = '". $periodo ."' ";
$query  .= " where id_vistoria = " . $id_vistoria . ";";
$db->setQuery($query);
$db->execute();

$query   = " delete from vistoriasEquipes where id_vistoria = ".$id_vistoria."; ";
$query  .= " insert into vistoriasEquipes( id_vistoria, id_equipe ) values ";
$query  .= " (".$id_vistoria.", ".$equipes.");";

/*
for($i = 0; $i < count($equipes); $i++){
	if($equipes[$i] != ""){
		$query  .= "insert into vistoriasEquipes( id_vistoria, id_equipe ) values ( ";
		$query  .= " '". $id_vistoria ."', ";
		$query  .= " '". $equipes[$i] ."' ";
		$query  .= " ); ";
	}
}
*/

$db->setQuery($query);
$db->execute();

$query  = " delete from ocorrencias where id_vistoria = " . $id_vistoria .";";

for ($i = 0; $i < count($lista_pontos); $i++){
	$query  .= " insert into ocorrencias (id_vistoria, id_ponto) values ( ";
	$query  .= $id_vistoria . ", " . $lista_pontos[$i];
	$query  .= " );  ";
}

$query  .= " CREATE SEQUENCE seq_vistoria_". $id_vistoria ."_pontos INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1; ";
$query  .= " update ocorrencias set posicao = nextval('seq_vistoria_". $id_vistoria ."_pontos'::regclass) where id_vistoria = ". $id_vistoria ."; ";
$query  .= " DROP SEQUENCE IF EXISTS seq_vistoria_". $id_vistoria ."_pontos CASCADE; ";

$query  .= " delete from vistoriasRoteiros where id_vistoria = " . $id_vistoria .";";

$query  .= " insert into vistoriasRoteiros( id_vistoria, id_roteiro, qtd_pontos )  ";
$query  .= " select bla.id_vistoria, bla.id_roteiro, bla.qtd_pontos from ( ";
$query  .= "	select ". $id_vistoria ." as id_vistoria, pontos.id_roteiro, count(ocorrencias.id_ponto) as qtd_pontos ";
$query  .= "	from ocorrencias ";
$query  .= "	inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
$query  .= "	inner join roteiros on roteiros.id_roteiro = pontos.id_roteiro ";
$query  .= "	where ocorrencias.id_vistoria = ". $id_vistoria ." ";
$query  .= "	group by pontos.id_roteiro ";
$query  .= " ) as bla where bla.qtd_pontos > 0 ; ";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>