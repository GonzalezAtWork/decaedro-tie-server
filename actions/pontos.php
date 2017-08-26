<?php
include("classes/paginacao.php");

$paginacao = new Paginacao;

#Query principal
$query  = " select a.*, b.nome as padrao_nome, c.nome as tipo_nome, c.totem as tipo_totem, s.status_nome from pontos a ";
$query .= " left join pontosPadrao b on a.id_padrao = b.id_padrao ";
$query .= " left join pontosTipo c on b.id_tipo = c.id_tipo ";
$query .= " inner join (";
$query .= " select a.id_ponto, C.nome as status_nome from pontosStatusHistorico a ";
$query .= " inner join (";
$query .= " select id_ponto, max(data) as data from pontosStatusHistorico group by id_ponto) b on b.id_ponto = a.id_ponto and b.data = a.data ";
$query .= " inner join pontosStatus c on a.id_status = c.id_status) s on a.id_ponto = s.id_ponto  ";
$query .= " where a.ativo = true ";

$paginacao->query = $query;

$paginacao->thisAction = 'pontos';
$paginacao->titulo = "Pontos de Parada";
$paginacao->js_file = "javascript/pontos.js";
$paginacao->idField = 'id_ponto';

$paginacao->aFilterField = array( "codigo_abrigo", "endereco", "codigo_novo", "c.nome", "s.status_nome" );
$paginacao->aFilterArray = array( "SIMAK", "Ponto de Parada", "OTIMA", "Tipo", "Status");
$paginacao->aLabelArray  = array( "SIMAK", "Ponto de Parada", "OTIMA", "Tipo", "Status");
$paginacao->aQueryArray  = array( "codigo_abrigo", "endereco", "codigo_novo", "tipo_nome", "status_nome" );


$paginacao->widths		 = array( 100,100,100,100,100,100,100,100,100,100,100,100,100);
$paginacao->alignments   = array("center","center","center","center","center","center","center","center","center","center","center","center","center");

$paginacao->ButtonsHasRecords = '';
$paginacao->ButtonsHasRecords .= '<input type="button" name="edit" id="edit" value="Editar">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="insert" id="insert" value="Incluir">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="delete" id="delete" value="Excluir">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="import" id="import" value="Importar">';

$paginacao->ButtonsHasNoRecords = '<input type="button" name="insert" id="insert" value="Incluir">';
$paginacao->ButtonsHasNoRecords .= '<input type="button" name="import" id="import" value="Importar">';

#Conecta na base de dados
$paginacao->db = Database::getInstance();

$retorno = $paginacao->load( $app, $filterField, $orderField, $desc, $filterText, $orderField, $desc, $qtd_por_pagina, $offset, $currentPage );

echo $retorno;
?>
<?php

#Query principal
$query  = " select a.*, b.nome as padrao_nome, c.nome as tipo_nome, c.totem as tipo_totem, s.status_nome from pontos a ";
$query .= " left join pontosPadrao b on a.id_padrao = b.id_padrao ";
$query .= " left join pontosTipo c on b.id_tipo = c.id_tipo ";
$query .= " inner join (";
$query .= " select a.id_ponto, C.nome as status_nome from pontosStatusHistorico a ";
$query .= " inner join (";
$query .= " select id_ponto, max(data) as data from pontosStatusHistorico group by id_ponto) b on b.id_ponto = a.id_ponto and b.data = a.data ";
$query .= " inner join pontosStatus c on a.id_status = c.id_status) s on a.id_ponto = s.id_ponto  ";
$query .= " where a.ativo = true ";

$thisAction = 'pontos';
$titulo = "Pontos de Parada";
$js_file = "javascript/pontos.js";
$idField = 'id_ponto';

$aFilterField = array( "codigo_abrigo", "endereco", "codigo_novo", "c.nome", "s.status_nome" );
$aFilterArray = array( "SIMAK", "Ponto de Parada", "OTIMA", "Tipo", "Status");
$aLabelArray  = array( "SIMAK", "Ponto de Parada", "OTIMA", "Tipo", "Status");
$aQueryArray  = array( "codigo_abrigo", "endereco", "codigo_novo", "tipo_nome", "status_nome" );

##########################################################################################################
##########################################################################################################
##########################################################################################################
#                          SOMENTE EDITAR SE NECESSÁRIO ALGUMA CUSTOMIZAÇÃO NA TELA                      #
##########################################################################################################
##########################################################################################################
##########################################################################################################


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

	<fieldset>

		<legend><?php echo $titulo;?></legend>

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
				<tbody><tr><td id="noRecords">Nenhum registro encontrado<?php echo ($tableHasRecords ? ' para o filtro atual.' : '.');?></td></tr></tbody>
			</table>
			<?php
		} else {
			?>
			<table cellspacing="1" cellpadding="0" width="100%" border="0">
				<thead>
					<tr>
						<th id="radioheader">&nbsp;</th>
						<?php
						$contador = 1;
						foreach ($aLabelArray as $label) {
							echo '<th ';
							echo '	align="center" ';
							echo '	class="order" ';
							echo '	onClick="changeOrderBy('. $contador .', '. $orderField. ', '. $filterField .", '". $filterText. "', ".$desc .');" ';
							echo '>';
							echo $label;
							echo '&nbsp;&nbsp;';
							echo arrow($orderField, $contador, $desc);
							echo '</th>';
							$contador++;
						}
						?>
					</tr>
				</thead>

				<tbody>
					<?php
					foreach ($result as $row) {
						$html .= '<tr class="tableRow">';
						$html .= '<td align="center" width="20"><input type="radio" name="'. $idField .'" value="'.$row[ $idField ].'"></td>';
						foreach ($aQueryArray as $campo) {
							$html .= '<td>'. $row[ $campo ] .'</td>';
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

		$totalRecords = $db->getRows();

		#Número de páginas a mostrar
		$totalPages = ceil($totalRecords / $app->recordsToShow);

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

			echo '<span style="float:left;">Mostrando <b>'.number_format($totalRecords, 0, ',', '.').'</b> registros divididos em <b>'.number_format($totalPages, 0, ',', '.').'</b> páginas.</span>';

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

		<div id="buttons">
			<?php
			if ($tableHasRecords) {
				echo '<input type="button" name="edit" id="edit" value="Editar">';
				echo '<input type="button" name="insert" id="insert" value="Incluir">';
				echo '<input type="button" name="delete" id="delete" value="Excluir">';
			} else {
				echo '<input type="button" name="insert" id="insert" value="Incluir">';
			}
			echo '<input type="button" name="import" id="import" value="Importar">';
			?>
		</div>

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