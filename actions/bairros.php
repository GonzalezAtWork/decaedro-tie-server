<?php
include("classes/paginacao.php");

$paginacao = new Paginacao;

#Query principal
$query  = " select ";
$query .= "		b.id_bairro, b.nome, b.vistoria, ( b.distancia / 1000 )::text || ' km' as distancia, z.nome as zona,  ";
$query .= "		case when (b.vistoria = 'D') then 'Diária' else  ";
$query .= "			case when (b.vistoria = 'M') then 'Mensal' else  ";
$query .= "				case when (b.vistoria = 'T') then 'Trimestral' else  ";
$query .= "				'' ";
$query .= "				end ";
$query .= "			end  ";
$query .= "		end as vistoria_nome ";
$query .= "	from bairros b  ";
$query .= " left join zonas z on b.id_zona = z.id_zona ";
$query .= " where b.ativo = TRUE ";

$paginacao->query = $query;

$paginacao->thisAction = 'bairros';
$paginacao->titulo = "Bairros";
$paginacao->js_file = "javascript/bairros.js";
$paginacao->idField = 'id_bairro';

$paginacao->aFilterField = array( "b.nome", "z.nome", "b.vistoria", "b.distancia" );
$paginacao->aFilterArray = array( "Bairro", "Zona", "Vistoria", "Distância" );
$paginacao->aLabelArray  = array( "Bairro", "Zona", "Vistoria", "Distância" );
$paginacao->aQueryArray  = array( "nome", "zona", "vistoria_nome", "distancia" );


$paginacao->widths		 = array( 100,100,100,100,100,100,100,100,100,100,100,100,100);
$paginacao->alignments   = array("center","center","center","center","center","center","center","center","center","center","center","center","center");

$paginacao->ButtonsHasRecords = '';
$paginacao->ButtonsHasRecords .= '<input type="button" name="edit" id="edit" value="Editar">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="insert" id="insert" value="Incluir">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="delete" id="delete" value="Excluir">';

$paginacao->ButtonsHasNoRecords = '<input type="button" name="insert" id="insert" value="Incluir">';

#Conecta na base de dados
$paginacao->db = Database::getInstance();

$retorno = $paginacao->load( $app, $filterField, $orderField, $desc, $filterText, $orderField, $desc, $qtd_por_pagina, $offset, $currentPage );

echo $retorno;
?>