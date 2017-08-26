<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_ponto = $_REQUEST['id_ponto'];
$codigo_abrigo = $_REQUEST['codigo_abrigo'];
$codigo_novo = $_REQUEST['codigo_novo'];
$id_padrao = $_REQUEST['id_padrao'];
$endereco = $_REQUEST['endereco'];
$cep = $_REQUEST['cep'];
$noturno = $_REQUEST['noturno'];

$id_regional = $_REQUEST['id_regional'];
$posicao_roteiro = $_REQUEST['posicao_roteiro'];
if($posicao_roteiro == "") $posicao_roteiro = 'null';
$posicao_global = $_REQUEST['posicao_global'];
if($posicao_global == "") $posicao_global = 'null';

$id_roteiro = $_REQUEST['id_roteiro'];
$id_bairro = $_REQUEST['id_bairro'];
$dt_implantacao = $_REQUEST['dt_implantacao'];
$painel_calcada = $_REQUEST['painel_calcada'];
$dt_painel_calcada = $_REQUEST['dt_painel_calcada'];
$observacoes = $_REQUEST['observacoes'];
$conjugados = $_REQUEST['conjugados'];
$id_inclinacao = $_REQUEST['id_inclinacao'];
$id_limite_terreno = $_REQUEST['id_limite_terreno'];
$limite_terreno_obs = $_REQUEST['limite_terreno_obs'];
$id_piso_calcada = $_REQUEST['id_piso_calcada'];
$piso_calcada_obs = $_REQUEST['piso_calcada_obs'];
$poste = $_REQUEST['poste'];
$poste_quantos = $_REQUEST['poste_quantos'];
$eletrica = $_REQUEST['eletrica'];
$secundario = $_REQUEST['secundario'];
$iluminacao_publica = $_REQUEST['iluminacao_publica'];
$largura_calcada = $_REQUEST['largura_calcada'];
$distancia_calcada = $_REQUEST['distancia_calcada'];
$gmaps_longitude = $_REQUEST['gmaps_longitude'];
$gmaps_latitude = $_REQUEST['gmaps_latitude'];
$croquis = $_REQUEST['croquis'];

$interferencia_E_codigo = $_REQUEST['interferencia_E_codigo'];
$interferencia_D_codigo = $_REQUEST['interferencia_D_codigo'];
$interferencia_E = $_REQUEST['interferencia_E'];
$interferencia_D = $_REQUEST['interferencia_D'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conex?o ao banco de dados
$db = Database::getInstance();

$query  = " update pontos set ";
$query .= " codigo_abrigo = '" . $codigo_abrigo ."', ";
$query .= " codigo_novo = '" . $codigo_novo ."', ";
if($id_padrao != "") $query .= " id_padrao = " . $id_padrao .", ";
$query .= " endereco = '" . $endereco ."', ";
$query .= " cep = '" . $cep ."', ";
$query .= " noturno = '" . $noturno ."', ";
$query .= " posicao_global = " . $posicao_global .", ";
if($id_roteiro != "") $query .= " id_roteiro = " . $id_roteiro .", ";
if($id_regional != "") $query .= " id_regional = " . $id_regional .", ";
if($id_bairro != "") $query .= " id_bairro = " . $id_bairro .", ";
if($dt_implantacao != "") $query .= " dt_implantacao = '" . $dt_implantacao ."', ";
$query .= " painel_calcada = '" . $painel_calcada ."', ";
if($dt_painel_calcada != "") $query .= " dt_painel_calcada = '" . $dt_painel_calcada ."', ";
$query .= " observacoes = '" . $observacoes ."', ";
$query .= " conjugados = '" . $conjugados ."', ";
if($id_inclinacao != "") $query .= " id_inclinacao = " . $id_inclinacao .", ";
if($id_limite_terreno != "") $query .= " id_limite_terreno = " . $id_limite_terreno .", ";
$query .= " limite_terreno_obs = '" . $limite_terreno_obs ."', ";
if($id_piso_calcada != "") $query .= " id_piso_calcada = " . $id_piso_calcada .", ";
$query .= " piso_calcada_obs = '" . $piso_calcada_obs ."', ";
$query .= " poste = '" . $poste ."', ";
$query .= " poste_quantos = '" . $poste_quantos ."', ";
$query .= " eletrica = '" . $eletrica ."', ";
$query .= " secundario = '" . $secundario ."', ";
$query .= " iluminacao_publica = '" . $iluminacao_publica ."', ";
$query .= " largura_calcada = '" . $largura_calcada ."', ";
$query .= " distancia_calcada = '" . $distancia_calcada ."', ";
$query .= " gmaps_longitude = '" . $gmaps_longitude ."', ";
$query .= " gmaps_latitude = '" . $gmaps_latitude ."', ";
$query .= " croquis = '" . $croquis ."' ";
$query .= " where id_ponto = " . $id_ponto;

$query .= "; delete from roteirosPontos where id_ponto = " . $id_ponto. ";" .chr(13) .chr(13);
$query .= " insert into roteirosPontos( id_ponto, id_roteiro, posicao) values ('". $id_ponto ."','". $id_roteiro ."',". $posicao_roteiro .") ";

$query .= "; delete from pontosInterferencias where id_ponto = " . $id_ponto. ";" .chr(13) .chr(13);


for($i = 0; $i < count($interferencia_E_codigo); $i++){
	if($interferencia_E[$i] != "" && $interferencia_E[$i] != 'NaN'){
		$query .= " insert into pontosInterferencias( id_ponto,id_interferencia,tipo, metragem) ";
		$query .= " values(". $id_ponto .", ". $interferencia_E_codigo[$i] .",'E', '". $interferencia_E[$i] ."');" .chr(13);
	}
}

for($i = 0; $i < count($interferencia_D_codigo); $i++){
	if($interferencia_D[$i] != "" && $interferencia_D[$i] != 'NaN'){
		$query .= " insert into pontosInterferencias( id_ponto,id_interferencia,tipo, metragem) ";
		$query .= " values(". $id_ponto .", ". $interferencia_D_codigo[$i] .",'D', '". $interferencia_D[$i] ."');" .chr(13);
	}
}

$db->setQuery($query);

$db->execute();

$dados = $db->getResultSet();

//mostrar a query no resultado do ajax
//echo $query;

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);


?>