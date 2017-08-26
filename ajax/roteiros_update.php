<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_roteiro = $_REQUEST['id_roteiro'];

$reset = (isset($_REQUEST['reset']))?$_REQUEST['reset']:'';

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

if($reset == ''){
	$tipo = $_REQUEST['tipo'];

	$nome = $_REQUEST['nome'];
	$cor = $_REQUEST['cor'];

	$noturno = $_REQUEST['noturno'];

	$vistoria = $_REQUEST['vistoria'];
	$manutencao = $_REQUEST['manutencao'];
	$publicidade = $_REQUEST['publicidade'];
	$id_gravidade = $_REQUEST['id_gravidade'];

	$lavagem = $_REQUEST['lavagem'];
	$frequencia = $_REQUEST['frequencia'];

	$lista_pontos = $_REQUEST['lista_pontos'];


	$query  = " update roteiros set ";
	$query .= " nome = '" . $nome ."' ";
	$query .= " ,cor = '" . $cor ."' ";
	$query .= " ,noturno = '" . $noturno ."' ";
	$query .= " ,vistoria = '" . $vistoria ."' ";
	$query .= " ,manutencao = '" . $manutencao ."' ";
	$query .= " ,id_gravidade = '" . $id_gravidade ."' ";
	$query .= " ,publicidade = '" . $publicidade ."' ";
	$query .= " ,lavagem = '" . $lavagem ."' ";
	$query .= " ,frequencia = '" . $frequencia ."' ";
	$query .= " where id_roteiro = " . $id_roteiro;
	$query  .= "; delete from roteirosPontos where id_roteiro = " . $id_roteiro .";";
	for($i = 0; $i < count($lista_pontos); $i++){
		$query  .= " insert into roteirosPontos (id_roteiro, id_ponto) values ( ";
		$query  .= $id_roteiro . ", " . $lista_pontos[$i];
		$query  .= " );  ";
	}
	$query  .= " CREATE SEQUENCE seq_roteiro_". $id_roteiro ."_pontos INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1; ";
	$query  .= " update roteirosPontos set posicao = nextval('seq_roteiro_". $id_roteiro ."_pontos'::regclass) where id_roteiro = ". $id_roteiro ."; ";
	$query  .= " DROP SEQUENCE IF EXISTS seq_roteiro_". $id_roteiro ."_pontos CASCADE; ";

	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();

	//mostrar a query no resultado do ajax
	////echo $query;

	if($tipo == 'gera_vistoria'){
		$query   = "";
		$query  .= "insert into vistorias( data, periodo, id_gravidade ) values ( now(),'D','". $id_gravidade ."' ) returning id_vistoria; ";
		$db->setQuery($query);
		$db->execute();
		$dados = $db->getResultSet();
		$id_vistoria = $dados[0]['id_vistoria'];

		$query   = "";
		$query  .= "insert into ocorrencias (id_vistoria, id_ponto)";
		$query  .= " select '". $dados[0]['id_vistoria'] ."' as id_vistoria, id_ponto from roteirosPontos where id_roteiro = '". $id_roteiro ."';";
		$query  .= " CREATE SEQUENCE seq_vistoria_". $dados[0]['id_vistoria'] ."_pontos INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1; ";
		$query  .= " update ocorrencias set posicao = nextval('seq_vistoria_". $dados[0]['id_vistoria'] ."_pontos'::regclass) where id_vistoria = ". $dados[0]['id_vistoria'] ."; ";
		$query  .= " DROP SEQUENCE IF EXISTS seq_vistoria_". $dados[0]['id_vistoria'] ."_pontos CASCADE; ";

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
	}

	//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
	echo json_encode($dados[0]);
}else{
	$query = "delete from roteirosPontos where id_roteiro = " . $id_roteiro .";";
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	echo json_encode($dados[0]);
}
?>