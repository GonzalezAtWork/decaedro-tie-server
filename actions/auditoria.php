<?php
include("classes/paginacao.php");

$paginacao = new Paginacao;

#Query principal
$query  = " select auditoria.*, u.nome, auditoriaacoes.nome as acao_nome  ";
$query .= " from auditoria ";
$query .= " inner join usuarios u on auditoria.id_usuario = u.id_usuario ";
$query .= " inner join auditoriaacoes on auditoria.acao = auditoriaacoes.acao ";
$query .= " where 0 = 0 ";
//$query .= " order by data desc; ";

$paginacao->query = $query;
$paginacao->thisAction = 'auditoria';
$paginacao->titulo = "Auditoria";
$paginacao->js_file = "javascript/auditoria.js";
$paginacao->idField = 'data';

$paginacao->aFilterField = array(  "data","ip_address", "nome", "acao_nome", "obs" );
$paginacao->aFilterArray = array( "Data", "Aparelho", "Usuário", "Ação", "Observação" );
$paginacao->aLabelArray = array( "Data", "Aparelho", "Usuário", "Ação", "Observação" );
$paginacao->aQueryArray = array( "data","ip_address", "nome", "acao_nome", "obs" );
$paginacao->widths		 = array( 100,100,100,100,100,100,100,100,100,100,100,100,100);
$paginacao->alignments   = array("center","center","center","center","center","center","center","center","center","center","center","center","center");

$paginacao->ButtonsHasRecords = '';
$paginacao->ButtonsHasNoRecords = '';

#Conecta na base de dados
$paginacao->db = Database::getInstance();

$retorno = $paginacao->load( $app, $filterField, $orderField, $desc, $filterText, $orderField, $desc, $qtd_por_pagina, $offset, $currentPage );

echo $retorno;
?>