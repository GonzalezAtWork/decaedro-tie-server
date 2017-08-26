<?php

include("classes/paginacao.php");

$paginacao = new Paginacao;

#Query principal
$query  = " select a.*, b.nome as tipo_nome, b.totem as tipo_totem from pontosPadrao a ";
$query .= " left join pontosTipo b on a.id_tipo = b.id_tipo ";
$query .= " where a.ativo = TRUE ";

$paginacao->query = $query;

$paginacao->thisAction = 'pontosPadrao';
$paginacao->titulo = "Padrões de Pontos de Parada";
$paginacao->js_file = "javascript/pontosPadrao.js";
$paginacao->idField = 'id_padrao';

$paginacao->aFilterField = array( "a.nome", "b.nome" );
$paginacao->aFilterArray = array( "Padrão", "Tipo" );
$paginacao->aLabelArray  = array( "Padrão", "Tipo" );
$paginacao->aQueryArray  = array( "nome", "tipo_nome" );

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