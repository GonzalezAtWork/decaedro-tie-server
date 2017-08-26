<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_vistoria = (isset($_REQUEST['id_vistoria']))?$_REQUEST['id_vistoria']:"";
$id_roteiro = (isset($_REQUEST['id_roteiro']))?$_REQUEST['id_roteiro']:"";
$id_tipo = (isset($_REQUEST['id_tipo']))?$_REQUEST['id_tipo']:"";
$id_bairro = (isset($_REQUEST['id_bairro']))?$_REQUEST['id_bairro']:"";

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$query   = "";
$query  .= " insert into ocorrencias (id_vistoria, id_ponto) ";
$query  .= " select '". $id_vistoria ."' as id_vistoria, id_ponto from pontos  ";
$query  .= " where  ";
$query  .= " id_roteiro = '". $id_roteiro ."' and  ";
if( $id_bairro != "" ){
	$query  .= " id_bairro = '". $id_bairro ."' and  ";
}
//$query  .= " char_length(gmaps_latitude) > 10 and ";
$query  .= " id_padrao in ( ";
$query  .= " 	select id_padrao from pontospadrao where id_tipo = '". $id_tipo ."' ";
$query  .= " ) ";
$query  .= " and id_ponto not in (select id_ponto from ocorrencias where id_vistoria = '". $id_vistoria ."') ";

$db->setQuery($query);
$db->execute();

//Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);
?>