<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

//$id_vistoria = (isset($_REQUEST['id_vistoria']))?$_REQUEST['id_vistoria']:"";
//$executada = (isset($_REQUEST['executada']))?$_REQUEST['executada']:"";
$id_gravidade = (isset($_REQUEST['id_gravidade']))?$_REQUEST['id_gravidade']:"";
$data = (isset($_REQUEST['data']))?$_REQUEST['data']:"";
$periodo = (isset($_REQUEST['periodo']))?$_REQUEST['periodo']:"";
$tipos = (isset($_REQUEST['tipos']))?$_REQUEST['tipos']:"";
$equipes = (isset($_REQUEST['equipes']))?$_REQUEST['equipes']:"";
$qtd_roteiros = (isset($_REQUEST['qtd_roteiros']))?$_REQUEST['qtd_roteiros']:"";
$roteiros = (isset($_REQUEST['roteiros']))?$_REQUEST['roteiros']:"";

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = "insert into vistorias(";
$query  .= " data, ";
$query  .= " periodo, ";
$query  .= " id_gravidade ";
$query  .= ") values ( ";
$query  .= " '". $data ."', ";
$query  .= " '". $periodo ."', ";
$query  .= " '". $id_gravidade ."' ";
$query  .= " ) returning id_vistoria; ";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

if ($equipes != ""){
	$query  = "insert into vistoriasEquipes( id_vistoria, id_equipe ) values ";
	$query .= " (".$dados[0]['id_vistoria'] .", ". $equipes ."); ";

	$db->setQuery($query);
	$db->execute();
}
////echo $query;

if($tipos != ""){
	$query  = "";
	for($i = 0; $i < count($tipos); $i++){
		if($tipos[$i] != ""){
			$query  .= "insert into vistoriasTipos( id_vistoria, id_tipo ) values ( ";
			$query  .= " '". $dados[0]['id_vistoria'] ."', ";
			$query  .= " '". $tipos[$i] ."' ";
			$query  .= " ); ";
		}
	}
	$db->setQuery($query);
	$db->execute();
}
////echo $query;
if($roteiros != ""){
	for($i = 0; $i < count($roteiros); $i++){
		if($qtd_roteiros[$i] != ""){
			$query   = "";

			$query  .= " insert into ocorrencias (id_vistoria, id_ponto)  ";
			$query  .= " select '". $dados[0]['id_vistoria'] ."' as id_vistoria, id_ponto from pontos  ";
			$query  .= " inner join bairros on bairros.id_bairro = pontos.id_bairro ";
			$query  .= " where id_roteiro = ". $roteiros[$i];
			$query  .= " and pontos.id_padrao in ( ";
			$query  .= "	select pontosPadrao.id_padrao from pontosPadrao where pontosPadrao.id_tipo in (". implode(',',$tipos) .") ";
			$query  .= " ) ";
			$query  .= " and id_ponto not in ( ";
			$query  .= "	select id_ponto from ocorrencias ";
			// confirmar funcionamento de vistorias diarias, mensais e trimestrais
			// lembrar da quest�o de urgencia
			$query  .= "	where id_vistoria != " . $dados[0]['id_vistoria'];
			$query  .= "	and id_vistoria in (";
			$query  .= "		select id_vistoria from vistorias ";
			//se s� puder ser di�ria
			//$query  .= "			where data = '". $data ."'";
			// talvez algum tratamento por di�rio / mensal / trimestral usando o cadastro de bairro?
			$query  .= "			where agendada = TRUE and executada = FALSE ";
			$query  .= "	) ";
			$query  .= " ) ";
			// ordernar por CEP
			//$query  .= " order by distancia, cep ";
			// ordenar por GPS
			$query  .= " order by distancia, gmaps_latitude desc, gmaps_longitude ";
			$query  .= " LIMIT ". $qtd_roteiros[$i] ."; ";

			////echo $query;

			$query  .= " insert into vistoriasRoteiros( id_vistoria, id_roteiro, qtd_pontos )  ";
			$query  .= " select bla.id_vistoria, bla.id_roteiro, bla.qtd_pontos from ( ";
			$query  .= "	select ". $dados[0]['id_vistoria'] ." as id_vistoria, ". $roteiros[$i] ." as id_roteiro, count(ocorrencias.id_ponto) as qtd_pontos ";
			$query  .= "	from ocorrencias ";
			$query  .= "	inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
			$query  .= "	inner join roteiros on roteiros.id_roteiro = pontos.id_roteiro ";
			$query  .= "	where pontos.id_roteiro = ". $roteiros[$i] ." and ocorrencias.id_vistoria = ". $dados[0]['id_vistoria'] ." ";
			$query  .= " ) as bla where bla.qtd_pontos > 0 ; ";

			$db->setQuery($query);
			$db->execute();
		}
	}
}

$query   = "";
$query  .= " CREATE SEQUENCE seq_vistoria_". $dados[0]['id_vistoria'] ."_pontos INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1; ";
$query  .= " update ocorrencias set posicao = nextval('seq_vistoria_". $dados[0]['id_vistoria'] ."_pontos'::regclass) where id_vistoria = ". $dados[0]['id_vistoria'] ."; ";
$query  .= " DROP SEQUENCE IF EXISTS seq_vistoria_". $dados[0]['id_vistoria'] ."_pontos CASCADE; ";
$db->setQuery($query);
$db->execute();

////echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);
?>