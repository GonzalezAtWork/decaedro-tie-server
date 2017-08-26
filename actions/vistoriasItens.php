<?php

include("classes/paginacao.php");

$paginacao = new Paginacao;

#Query principal
$query  = " select ";
$query .= " vistoriasItens.*, itensTipo.nome as tipo, case when vistoriasItens.id_tipoitem = 0 then 999 else  vistoriasItens.id_tipoitem end as ordem ";
$query .= " from vistoriasItens";
$query .= " left join itensTipo on vistoriasItens.id_tipoitem = itensTipo.id_tipoitem ";
$query .= " where  vistoriasItens.ativo = TRUE ";

$paginacao->query = $query;

$paginacao->thisAction = 'vistoriasItens';
$paginacao->titulo = "Itens de Vistoria";
$paginacao->js_file = "javascript/vistoriasItens.js";
$paginacao->idField = 'id_item';

$paginacao->aFilterField = array( "vistoriasItens.nome","sigla", "itensTipo.nome");
$paginacao->aFilterArray = array( "Nome do Item", "Sigla", "Tipo");
$paginacao->aLabelArray  = array( "Nome do Item", "Sigla", "Tipo");
$paginacao->aQueryArray  = array( "nome", "sigla", "tipo");

$paginacao->widths		 = array( 100,100,100,100,100,100,100,100,100,100,100,100,100);
$paginacao->alignments   = array("center","center","center","center","center","center","center","center","center","center","center","center","center");

$paginacao->ButtonsHasRecords = '';
$paginacao->ButtonsHasRecords .= '<input type="button" name="edit" id="edit" value="Editar">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="insert" id="insert" value="Incluir">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="delete" id="delete" value="Excluir">';

			echo '<input type="button" name="import" id="import" value="Importar">';

$paginacao->ButtonsHasNoRecords = '<input type="button" name="insert" id="insert" value="Incluir">';

#Conecta na base de dados
$paginacao->db = Database::getInstance();

$retorno = $paginacao->load( $app, $filterField, $orderField, $desc, $filterText, $orderField, $desc, $qtd_por_pagina, $offset, $currentPage );

echo $retorno;
?>