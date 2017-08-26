<?php

#Query principal
$query  = "select * from perfis where ativo = TRUE";

$thisAction = "perfis";
$titulo = "Perfis";
$js_file = "javascript/perfis.js";
$idField = "id_perfil";

$aFilterField = array( "nome");
$aFilterArray = array( "Nome");
$aLabelArray  = array( "Nome");
$aQueryArray  = array( "nome");

##########################################################################################################
##########################################################################################################
##########################################################################################################
#                          SOMENTE EDITAR SE NECESS�RIO ALGUMA CUSTOMIZA��O NA TELA                      #
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

	<fieldset id="medium">

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
						<th id="radioheader">&nbsp;</th>
						<?php
						$contador = 1;
						foreach ($aLabelArray as $label) {
							echo '<th ';
							echo '	align="left" ';
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

		if (($rangeStart <= 1) && ($rangeFinish >= $totalPages)) {
			#Corrige as páginas iniciais e finais ao mesmo tempo
			$rangeStart = 1;
			$rangeFinish = $totalPages;
		} else if ($rangeStart <= 1) {
			#Corrige a partir da página inicial
			$rangeStart = 1;
			$rangeFinish = $rangeLenght;
		} else if ($rangeFinish >= $totalPages) {
			#Corrige a partir da página final
			$rangeFinish = $totalPages;
			$rangeStart = $rangeFinish - $rangeLenght + 1;
		}

		echo '<div id="pagination">';

		if ($totalPages > 1) {

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

		} else {

			echo '<span style="float:left;">Mostrando <b>'.number_format($totalRecords, 0, ',', '.').'</b> registros.</span>';

		}

		echo '</div>';
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