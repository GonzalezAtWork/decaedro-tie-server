<?php
include("classes/paginacao.php");

$paginacao = new Paginacao;

#Query principal
$query  = "";

$query .= " select * from ( ";

$query .= " select ";
$query .= " dt_lastupdate, id_ocorrencia, roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos, ";
$query .= " string_agg(vistoriasitens.nome,', ') as itens";
//$query .= " vistoriasitens.nome as itens";
$query .= " from (";
$query .= " select ";
$query .= "		dt_lastupdate, ";
$query .= "		'<a target=_blank href=home.php?action=ocorrencias_edit&id_ocorrencia='|| id_ocorrencia ||'>' || id_ocorrencia::varchar || '</a>' as id_ocorrencia, ";
$query .= "		roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos,";
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
$query .= " 	hora, ";
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
$query .= " 			to_char(ocorrencias.dt_lastupdate,'dd/MM/yyyy HH24:MI') ";
$query .= " 		else ";
$query .= " 			to_char(ocorrencias.data,'dd/MM/yyyy HH24:MI') ";
$query .= " 		end as data, ";
$query .= " 	case when ocorrencias.dt_lastupdate is not null then ";
$query .= " 			to_char(ocorrencias.dt_lastupdate,'HH24:MI') ";
$query .= " 		else ";
$query .= " 			to_char(ocorrencias.data,'HH24:MI') ";
$query .= " 		end as hora, ";
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
$query .= " ) as x ";
$query .= " ) as y";
$query .= " where encarregado != 'SISTEMA'";
$query .= " ) as ocorrencias";
$query .= " left join vistoriasitens on vistoriasitens.id_item = ocorrencias.itens";
$query .= " group by dt_lastupdate, id_ocorrencia, roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos";

$query .= " ) as q";
$query .= " where 0 = 0  ";

//echo $query;

$paginacao->thisAction = 'relatorio_geral';
$paginacao->titulo = "Relatório Geral de Atividades";
$paginacao->js_file = "javascript/relatorio_geral.js";
$paginacao->idField = 'id_ocorrencia';

$paginacao->aFilterField = array(  "dt_lastupdate","id_ocorrencia", "encarregado", "simak", "otima", "endereco", "roteiro", "itens", "observacao", "qtd_fotos", "tipo" );
$paginacao->aFilterArray = array( "Data", "Ocorrência", "Encarregado", "SIMAK", "OTIMA", "Endereço", "Roteiro", "Itens", "Observação", "Fotos", "Tipo" );
$paginacao->aLabelArray = array( "Data", "Ocorrência", "Encarregado", "SIMAK", "OTIMA", "Endereço", "Roteiro", "Itens", "Observação", "Fotos", "Tipo" );
$paginacao->aQueryArray = array( "data", "id_ocorrencia", "encarregado", "simak", "otima", "endereco", "roteiro", "itens", "observacao", "qtd_fotos", "tipo" );


$paginacao->query = $query;
$paginacao->widths		 = array( 100,100,100,100,100,100,100,100,100,100,100,100,100);
$paginacao->alignments   = array("center","center","center","center","center","center","center","center","center","center","center","center","center");

$paginacao->ButtonsHasRecords = '';
$paginacao->ButtonsHasNoRecords = '';

#Conecta na base de dados
$paginacao->db = Database::getInstance();

$retorno = $paginacao->load( $app, $filterField, $orderField, $desc, $filterText, $orderField, $desc, $qtd_por_pagina, $offset, $currentPage );

echo $retorno;
?>
<?php

#Query principal
$query  = "";

$query .= " select * from ( ";

$query .= " select ";
$query .= " dt_lastupdate, id_ocorrencia, roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos, ";
$query .= " string_agg(vistoriasitens.nome,', ') as itens";
//$query .= " vistoriasitens.nome as itens";
$query .= " from (";
$query .= " select ";
$query .= "		dt_lastupdate, ";
$query .= "		'<a target=_blank href=home.php?action=ocorrencias_edit&id_ocorrencia='|| id_ocorrencia ||'>' || id_ocorrencia::varchar || '</a>' as id_ocorrencia, ";
$query .= "		roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos,";
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
$query .= " 	hora, ";
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
$query .= " 			to_char(ocorrencias.dt_lastupdate,'dd/MM/yyyy HH24:MI') ";
$query .= " 		else ";
$query .= " 			to_char(ocorrencias.data,'dd/MM/yyyy HH24:MI') ";
$query .= " 		end as data, ";
$query .= " 	case when ocorrencias.dt_lastupdate is not null then ";
$query .= " 			to_char(ocorrencias.dt_lastupdate,'HH24:MI') ";
$query .= " 		else ";
$query .= " 			to_char(ocorrencias.data,'HH24:MI') ";
$query .= " 		end as hora, ";
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
$query .= " 	case when ";
//$query .= " 		ocorrencias.id_vistoria is not null and ";
$query .= " 		( ocorrencias.id_os is null or ocorrencias.id_os = 0 ) ";
$query .= " 	then 'VISTORIA' else ";
$query .= " 		case when observacao = 'TROCA DE CARTAZ - PUBLICIDADE' then ";
$query .= " 			'PUBLICIDADE' ";
$query .= " 		else ";
$query .= " 			'MANUTENÇÃO' ";
$query .= " 		end ";
$query .= " 	end as tipo ";
$query .= " from ocorrencias ";
$query .= " 	inner join pontos on ocorrencias.id_ponto = pontos.id_ponto ";
$query .= " 	left join roteiros on roteiros.id_roteiro = pontos.id_roteiro ";
$query .= " 	left join vistoriasequipes on ocorrencias.id_vistoria = vistoriasequipes.id_vistoria ";
$query .= " 	left join usuarios on vistoriasequipes.id_equipe = usuarios.id_usuario ";
$query .= " 	left join usuarios b on ocorrencias.id_equipe = b.id_usuario ";
$query .= " 	left join usuarios c on ocorrencias.id_usuario = c.id_usuario ";
$query .= " 	left join ( ";
$query .= " 		select id_ocorrencia, count(1) as qtd_fotos from fotografias group by id_ocorrencia ";
$query .= " 	) as foto on foto.id_ocorrencia = ocorrencias.id_ocorrencia ";
$query .= "		where ocorrencias.gerar_os = true ";
$query .= " ) as x ";
$query .= " ) as y ";
$query .= " where encarregado != 'SISTEMA' ";
$query .= " ) as ocorrencias ";
$query .= " left join vistoriasitens on vistoriasitens.id_item = ocorrencias.itens";
$query .= " group by dt_lastupdate, id_ocorrencia, roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos ";

$query .= " ) as q ";
$query .= " where 0 = 0  ";

//echo $query;

$thisAction = 'relatorio_geral';
$titulo = "Relatório Geral de Atividades";
$js_file = "javascript/relatorio_geral.js";
$idField = 'id_ocorrencia';

$aFilterField = array("dt_lastupdate","id_ocorrencia", "encarregado", "simak", "otima", "endereco", "roteiro", "itens", "observacao", "qtd_fotos", "tipo" );
$aFilterArray = array("Data", "Ocorrência", "Encarregado", "SIMAK", "OTIMA", "Endereço", "Roteiro", "Itens", "Observação", "Nº Fotos", "Tipo" );
$aLabelArray = array("Data", "Ocorrência", "Encarregado", "SIMAK", "OTIMA", "Endereço", "Roteiro", "Itens", "Observação", "Nº Fotos", "Tipo" );
$aQueryArray = array("data", "id_ocorrencia", "encarregado", "simak", "otima", "endereco", "roteiro", "itens", "observacao", "qtd_fotos", "tipo" );
$widths = array(30, 60, 120, 125, 100, 100, 100, 80, 100, 40, 80, 100);
$alignments = array("center", "center", "center", "left", "center", "center", "left", "center", "left", "left", "center", "center");

##########################################################################################################
##########################################################################################################
##########################################################################################################
#                          SOMENTE EDITAR SE NECESSÁRIO ALGUMA CUSTOMIZAÇÃO NA TELA                      #
##########################################################################################################
##########################################################################################################
##########################################################################################################

#Se tem a palavra "Data" no primeiro campo do grid, começa com ordem decrescente
if ((preg_match('/^Data/', $aLabelArray[0]) === 1) && (!isset($_REQUEST['desc']))) {
	$desc = "true";
}

#Conecta na base de dados
$db = Database::getInstance();

#Executa query
$db->setQuery($query. '  limit 1 ');

#Verificando se a tabela tem registros
$db->execute();

#Se tem ao menos um registro
if ($db->getRows() > 0) {
	$tableHasRecords = true;
} else {
	$tableHasRecords = false;
}
?>

<script type="text/javascript" src="<?php echo $js_file;?>"></script>

<form name="<?php echo $thisAction;?>" id="<?php echo $thisAction;?>" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="gigantic">

		<legend id="large"><?php echo $titulo;?></legend>

		<?php
		if ($tableHasRecords) {
			#--- Inicio do filtro ---#
			$drop = new Dropdown();
			$select = $drop->getHTMLFromArray($aFilterArray, $filterField, 'filterfield', FALSE);
			echo '<div id="filtercontainer">';
			echo '<input type="hidden" name="orderfield" id="orderfield" value="'.$orderField.'">';
			echo '<input type="hidden" name="desc" id="desc" value="'.$desc.'">';
			echo '<span id="filterlabel">'.$select.'</span>';
			echo '<span><input type="text" name="filtertext" id="filtertext" class="medium_field" value="'.$filterText.'"></span>';
			echo '<span><input type="button" name="filterbutton" id="filterbutton" value="Filtrar"></span>';
			echo '</div>';
			#--- Final do filtro ---#
		} else {
			echo "<br>";
		}

		#---- Inicio do filtro ----#
		if (!empty($filterText)) {
			$query .= " and ".$aFilterField[$filterField-1]." ilike '%".$filterText."%' ";
		}
		#---- Fim do filtro ---- #

		#---- Início do order by ---#
		$orderBy = " order by ".$aFilterField[$orderField-1];
		$orderBy .= ($desc === 'true' ? " DESC " : " ASC ");
		#--- Fim do order by ---#

		#--- Início do limit/offset ---#
		$limitOffset = " limit ". $qtd_por_pagina ." offset ".$offset.";";
		#--- Fim do limit/offset ---#

		#Executa query
		$db->setQuery($query.$orderBy.$limitOffset);
		$db->execute();

		$result = $db->getResultSet();

		$html = "";

		if (sizeof($result) <= 0) {
			?>
			<table cellspacing="1" cellpadding="0" width="100%" border="0">
				<thead><tr><th>&nbsp;</th></tr></thead>
				<tbody>
					<tr><td id="noRecords">Nenhum registro encontrado<?php echo ($tableHasRecords ? ' para o filtro atual.' : '.');?></td></tr>
				</tbody>
			</table>
			<?php
		} else {
			?>
			<table cellspacing="1" cellpadding="0" width="100%" border="0">
				<thead>
					<tr>
						<!-- <th id="radioheader">&nbsp;</th> -->
						<?php
						$counter = 1;
						foreach ($aLabelArray as $label) {
							echo '<th ';
							echo '   width="'.$widths[$counter].'"';
							echo '	align="'.$alignments[$counter].'" ';
							echo '	class="order" ';
							echo '	onClick="changeOrderBy('.$counter.', '.$orderField.', '.$filterField.", '".$filterText."', ".$desc .');" ';
							echo '>';
							echo $label;
							echo '&nbsp;&nbsp;';
							echo arrow($orderField, $counter++, $desc);
							echo '</th>';
						}
						?>
					</tr>
				</thead>

				<tbody>
					<?php
					foreach ($result as $row) {
						$html .= '<tr class="tableRow">';
						//$html .= '<td align="center" width="20"><input type="radio" name="'.$idField.'" value="'.$row[ $idField ].'"></td>';
						$counter = 1;
						foreach ($aQueryArray as $campo) {
							$html .= '<td align="'.$alignments[$counter++].'">'.$row[$campo].'</td>';
						}
						$html .= '</tr>';
					}
					echo $html;
					?>
				</tbody>
			</table>
			<?php
		}

		#Executa query de totalização para paginação, com a mesma query e filtros executados anteriormente
		$db->setQuery($query);
		$db->execute();

		#Número de páginas a mostrar
		$totalPages = ceil($db->getRows() / $app->recordsToShow);

		#Tamanho do range de páginas a ser mostrado
		$rangeLenght = $app->blockLenght;

		#Se o número de páginas é menor do que o range a ser mostrado
		if ($totalPages <= $rangeLenght) {

			#Acerta páginas inicial e final
			$rangeStart = 1;
			$rangeFinish = $totalPages;

		} else {

			#Acerta página inicial do range
			if (($currentPage + $rangeLenght) > $totalPages) {
				$rangeStart = $totalPages - $rangeLenght + 1;
			} else {
				$rangeStart = $currentPage - ceil($rangeLenght / 2);
			}

			#Range Start não pode nunca ser menor do que 1
			if ($rangeStart < 1) {
				$rangeStart = 1;
			}

			#Acerta página final do range
			$rangeFinish = $rangeStart + ($rangeLenght - 1);

			#Range Finish não pode nunca ser maior do que Total Pages
			if ($rangeFinish > $totalPages) {
				$rageFinish = $totalPages;
			}

		}

		if ($totalPages > 1) {

			echo '<div id="pagination">';

			if ($currentPage > 1) {
				echo '<span id="firstPage" title="Primeira página" onClick="javascript:newPage(1,'.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">&nbsp;</span>';
				echo '<span id="previousPage" title="Página anterior" onClick="javascript:newPage('.($currentPage-1).','.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">&nbsp;</span>';
			}

			for ($i = $rangeStart; $i <= $rangeFinish; $i++ ) {
				if ($i == $currentPage) {
					echo '<span class="page" title="Página atual" id="current">'.$i.'</span>';
				} else {
					echo '<span class="page" title="Página '.$i.'" onClick="javascript:newPage('.$i.','.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">'.$i.'</span>';
				}
			}

			if ($currentPage < $totalPages) {
				echo '<span id="nextPage" title="Próxima página" onClick="javascript:newPage('.($currentPage+1).','.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">&nbsp;</span>';
				echo '<span id="lastPage" title="Última página" onClick="javascript:newPage('.$totalPages.','.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">&nbsp;</span>';
			}

			echo '</div>';
		}
		?>
		<br>
	</fieldset>

</form>
<script language="javascript">
	var thisAction = '<?php echo $thisAction;?>';
	var idField = '<?php echo $idField;?>';

	//----------------------------------------//

	function newPage(currentPage, filterField, filterText, desc) {

		location.href = 'home.php?action=' + thisAction + '&page=' + currentPage + '&filterfield=' + filterField + '&filtertext=' + filterText + '&desc=' + desc;

	}

	//----------------------------------------//

	function changeOrderBy(newOrder, oldOrder, filterfield, filter, desc) {

		if (newOrder == oldOrder) {
			desc = !desc;
		} else {
			desc = 'false';
		}
		location.href = 'home.php?action=' + thisAction + '&orderfield=' + newOrder + '&filterfield=' + filterfield + '&filtertext=' + filter + '&desc=' + desc;
	}

//----------------------------------------//

$(document).ready(function() {
	$('#filtertext').focus();
	//----------------------------------------//
	//Evita que o ENTER submeta o formulário
	$('form').on('submit', function(event) {
		event.preventDefault();
	});
	//----------------------------------------//
	$(document).keydown(function(e) {
		e.stopPropagation();
		if (e.keyCode === 13) {
			$('#filterbutton').click();
		}
	});
	//----------------------------------------//
	$('#filterbutton').click(function() {
		if (isEmpty($('#filtertext').val())) {
			showMessage('Filtro inválido.<br>A carga de dados será feita sem nenhum filtro.', 'redirect', 'home.php?action=' + thisAction, 'long size');
		} else {
			var field = $('#filterfield option:selected').val();
			var orderfield = $('#orderfield').val();
			var text = $('#filtertext').val();
			var desc = $('#desc').val();
			location.href = 'home.php?action=' + thisAction + '&orderfield=' + orderfield + '&filterfield=' + field + '&filtertext=' + text + '&desc=' + desc;
		}
	});
	//----------------------------------------//
});
</script>