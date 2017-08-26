<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_vistoria = $_REQUEST['id_vistoria'];

$km_saida = $_REQUEST['km_saida'];
$km_chegada = $_REQUEST['km_chegada'];
$km_rodados = $_REQUEST['km_rodados'];
$hs_saida = $_REQUEST['hs_saida'];
$hs_chegada = $_REQUEST['hs_chegada'];
$hs_rodados = $_REQUEST['hs_rodados'];

$pontos = $_REQUEST['pontos'];

$gerar_os = $_REQUEST['gerar_os'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexao ao banco de dados
$db = Database::getInstance();

$query  = "";
$query  .= " update vistorias set ";
$query  .= " km_saida = '". $km_saida ."', ";
$query  .= " km_chegada = '". $km_chegada ."', ";
$query  .= " km_rodados = '". $km_rodados ."', ";
$query  .= " hs_saida = '". $hs_saida ."', ";
$query  .= " hs_chegada = '". $hs_chegada ."', ";
$query  .= " hs_rodados = '". $hs_rodados ."' ";
$query  .= " where id_vistoria = " . $id_vistoria . ";";

//echo $query;
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

$query = "";
for($i = 0; $i < count($pontos); $i++){
	if($pontos[$i] != "" && $pontos[$i] != 'NaN'){
		$query .= " update ocorrencias set ";
		$query .= " vistoriada = true, ";
		$gera = "";
		for($c = 0; $c < count($gerar_os); $c++){
			if($gerar_os[$c] == $pontos[$i]){
				$gera = " gerar_os = true, ";
				$gera .= " itensVistoria = '" . $_REQUEST['itensVistoria_'.$pontos[$i]] ."', ";
				$gera .= " observacaovistoria = '" . $_REQUEST['observacaovistoria_'.$pontos[$i]] ."' ";
			}
		}
		if($gera == ""){
			$gera = " gerar_os = false, ";
			$gera .= " itensVistoria = null , ";
			$gera .= " observacaovistoria = null ";
		}
		$query .= $gera;
		$query .= " where id_vistoria = " . $id_vistoria . " and id_ponto = " . $pontos[$i]. ";";
	}
}

//echo '<textarea>'. $query .'</textarea>';
$db->setQuery($query);
$db->execute();

if($_REQUEST['acao'] == 'close' ){
	$query = "";
// Gera OSs

// Atualiza os pontos
	// GERAR OSS
	//$query .= " update ocorrencias set os_gerada = true ";
	//$query .= " where id_vistoria = ". $id_vistoria ." and vistoriada = true;";
// Atualiza Vistoria
	$query .= " update vistorias set executada = true ";
	$query .= " where id_vistoria = ". $id_vistoria .";";

	$db->setQuery($query);
	$db->execute();
}

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);

?>