<?php
include("classes/paginacao.php");

$paginacao = new Paginacao;

#Query principal
$query  = "";

$query .= " select * from ( ";

$query .= " select ";
$query .= " dt_lastupdate, id_ocorrencia, roteiro, tipo, data, encarregado, simak, otima, endereco, observacao, qtd_fotos, ";
$query .= " string_agg(vistoriasitens.nome,', ') as itens";
//$query .= " vistoriasitens.nome as itens";
$query .= " from (";
$query .= " select ";
$query .= "		dt_lastupdate, ";
$query .= "		'<a target=_blank href=home.php?action=ocorrencias_edit&id_ocorrencia='|| id_ocorrencia ||'>' || id_ocorrencia::varchar || '</a>' as id_ocorrencia, ";
$query .= "		roteiro, tipo, data, encarregado, simak, otima, endereco, observacao, qtd_fotos,";
$query .= "		unnest( string_to_array( replace( ";
$query .= "			case when itens is null then '0' else case when itens = '' then '0' else itens end end";
$query .= "		,' ' ,'') , ',') )::smallint as itens";
$query .= " ";
$query .= "  from ";
$query .= " (";

$query .= " select ";
$query .= " 	id_ocorrencia, ";
$query .= " 	roteiro, ";
$query .= " 	tipo, ";
$query .= " 	data, ";
$query .= " 	dt_lastupdate, ";
$query .= " 	case when tipo = 'VISTORIA' then ";
$query .= " 		case when vistoria_nome is null then ocorrencia_nome else vistoria_nome end ";
$query .= " 	else ";
$query .= " 		case when manutencao_nome is null then ocorrencia_nome else manutencao_nome end ";
$query .= " 	end as encarregado, ";
$query .= " 	codigo_abrigo as simak, ";
$query .= " 	codigo_novo as otima, ";
$query .= " 	endereco, ";
$query .= " 	case when tipo = 'VISTORIA' then ";
$query .= " 		itensvistoria ";
$query .= " 	else ";
$query .= " 		itensmanutencao ";
$query .= " 	end as itens, ";
$query .= " 	case when tipo = 'VISTORIA' then ";
$query .= " 		observacaovistoria ";
$query .= " 	else ";
$query .= " 		case when tipo = 'PUBLICIDADE' then ";
$query .= " 			observacao ";
$query .= " 		else ";
$query .= " 			observacaomanutencao ";
$query .= " 		end  ";
$query .= " 	end as observacao, ";
$query .= " 	qtd_fotos ";
$query .= " from  ";
$query .= " ( ";
$query .= " select ";
$query .= " 	ocorrencias.id_ocorrencia, ";
$query .= " 	roteiros.nome as roteiro, ";
$query .= " 	case when ocorrencias.dt_lastupdate is not null then ";
$query .= " 			ocorrencias.dt_lastupdate ";
$query .= " 		else ";
$query .= " 			ocorrencias.data ";
$query .= " 		end as dt_lastupdate, ";
$query .= " 	case when ocorrencias.dt_lastupdate is not null then ";
$query .= " 			to_char(ocorrencias.dt_lastupdate,'dd/MM/yyyy') ";
$query .= " 		else ";
$query .= " 			to_char(ocorrencias.data,'dd/MM/yyyy') ";
$query .= " 		end as data, ";
$query .= " 	c.nome as ocorrencia_nome,  ";
$query .= " 	usuarios.nome as vistoria_nome, ";
$query .= " 	b.nome as manutencao_nome, ";
$query .= " 	right('000000' || pontos.codigo_abrigo::text, 6) as codigo_abrigo , ";
$query .= " 	pontos.codigo_novo, ";
$query .= " 	pontos.endereco,	 ";
$query .= " 	replace(replace(ocorrencias.itensvistoria,' ', ''),',',', ') as itensvistoria , ";
$query .= " 	replace(replace(ocorrencias.itensmanutencao,' ', ''),',',', ') as itensmanutencao , ";
$query .= " 	ocorrencias.observacao, ";
$query .= " 	ocorrencias.observacaovistoria, ";
$query .= " 	ocorrencias.observacaomanutencao, ";
$query .= " 	foto.qtd_fotos, ";
$query .= " 	gerar_os, ";
$query .= " 	case when  ";
//$query .= " 		ocorrencias.id_vistoria is not null and ";
$query .= " 		( ocorrencias.id_os is null or ocorrencias.id_os = 0 )  ";
$query .= " 	then 'VISTORIA' else  ";
$query .= " 		case when observacao = 'TROCA DE CARTAZ - PUBLICIDADE' then ";
$query .= " 			'PUBLICIDADE' ";
$query .= " 		else ";
$query .= " 			'MANUTENÇÃO'  ";
$query .= " 		end ";
$query .= " 	end as tipo ";
$query .= " from ocorrencias  ";
$query .= " 	inner join pontos on ocorrencias.id_ponto = pontos.id_ponto ";
$query .= " 	left join roteiros on roteiros.id_roteiro = pontos.id_roteiro ";
$query .= " 	left join vistoriasequipes on ocorrencias.id_vistoria = vistoriasequipes.id_vistoria ";
$query .= " 	left join usuarios on vistoriasequipes.id_equipe = usuarios.id_usuario ";
$query .= " 	left join usuarios b on ocorrencias.id_equipe = b.id_usuario ";
$query .= " 	left join usuarios c on ocorrencias.id_usuario = c.id_usuario  ";
$query .= " 	left join ( ";
$query .= " 		select id_ocorrencia, count(1) as qtd_fotos from fotografias group by id_ocorrencia  ";
$query .= " 	) as foto on foto.id_ocorrencia = ocorrencias.id_ocorrencia ";
$query .= "		where ocorrencias.gerar_os = true ";
$query .= "		and ( ocorrencias.id_os is null or ocorrencias.id_os = 0 ) ";
$query .= " ) as x ";
$query .= " ) as y";
$query .= " where encarregado != 'SISTEMA'";
$query .= " ) as ocorrencias";
$query .= " left join vistoriasitens on vistoriasitens.id_item = ocorrencias.itens";
$query .= " group by dt_lastupdate, id_ocorrencia, roteiro, tipo, data, encarregado, simak, otima, endereco, observacao, qtd_fotos";

$query .= " ) as q";
$query .= " where 0 = 0  ";

$paginacao->query = $query;
$paginacao->thisAction = 'relatorio_geral';
$paginacao->titulo = "Ocorrências em Aberto";
$paginacao->js_file = "javascript/relatorio_geral.js";
$paginacao->idField = 'id_ocorrencia';

$paginacao->aFilterField = array(  "dt_lastupdate","id_ocorrencia", "encarregado", "simak", "otima", "endereco", "roteiro", "itens", "observacao", "qtd_fotos", "tipo" );
$paginacao->aFilterArray = array( "Data", "Ocorrência", "Encarregado", "SIMAK", "OTIMA", "Endereço", "Roteiro", "Itens", "Observação", "Fotos", "Tipo" );
$paginacao->aLabelArray = array( "Data", "Ocorrência", "Encarregado", "SIMAK", "OTIMA", "Endereço", "Roteiro", "Itens", "Observação", "Fotos", "Tipo" );
$paginacao->aQueryArray = array( "data", "id_ocorrencia", "encarregado", "simak", "otima", "endereco", "roteiro", "itens", "observacao", "qtd_fotos", "tipo" );
$paginacao->widths		 = array( 100,100,100,100,100,100,100,100,100,100,100,100,100);
$paginacao->alignments   = array("center","center","center","center","center","center","center","center","center","center","center","center","center");

$paginacao->ButtonsHasRecords = '';
$paginacao->ButtonsHasNoRecords = '';

#Conecta na base de dados
$paginacao->db = Database::getInstance();

$retorno = $paginacao->load( $app, $filterField, $orderField, $desc, $filterText, $orderField, $desc, $qtd_por_pagina, $offset, $currentPage );

echo $retorno;
?>