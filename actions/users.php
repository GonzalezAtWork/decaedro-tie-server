<?php
include("classes/paginacao.php");

$paginacao = new Paginacao;

$query  = " select ";
$query .= " u.id_usuario, ";
$query .= " initcap(lower(u.nome)) as nome, ";
$query .= " substr(u.cpf, 1, 3) || '.' || substr(u.cpf, 4, 3) || '.' || substr(u.cpf, 7, 3) || '-' || substr(u.cpf, 10, 2) as cpf, ";
$query .= " initcap(lower(u.nome_completo)) as nome_completo, ";
$query .= " u.email, ";
$query .= " u.ddd, ";
$query .= " substr(u.celular, 1, 3) || '-' || substr(u.celular, 4, 3) || '-' || substr(u.celular, 7, 3) as celular, ";
$query .= " p.nome as perfil ";
$query .= " from usuarios u ";
$query .= " inner join perfis p on p.id_perfil = u.id_perfil ";
$query .= " where id_usuario != 0 and u.ativo = TRUE ";

$paginacao->query = $query;
$paginacao->thisAction = 'users';
$paginacao->titulo = "Usuários";
$paginacao->js_file = "javascript/users.js";
$paginacao->idField = 'id_usuario';

$paginacao->aFilterField = array( "lower(u.nome)", "lower(p.nome)",  "cpf", "lower(u.nome_completo)", "lower(email)",    "ddd", "celular");
$paginacao->aFilterArray = array(          "Nome",        "Perfil",  "CPF",          "Nome Completo",       "E-mail",    "DDD", "Celular");
$paginacao->aLabelArray  = array(          "Nome",        "Perfil",  "CPF",          "Nome Completo",       "E-mail",    "DDD", "Celular");
$paginacao->aQueryArray  = array(          "nome",        "perfil",  "cpf",          "nome_completo",        "email",    "ddd", "celular");
$paginacao->widths		 = array( 30,         100,             100,    100,                      250,            200,       40,       80);
$paginacao->alignments   = array("center", "left",          "left", "left",                   "left",         "left", "center",   "left");

$paginacao->ButtonsHasRecords  = '';
$paginacao->ButtonsHasRecords .= '<input type="button" name="edit" id="edit" value="Editar">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="insert" id="insert" value="Incluir">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="delete" id="delete" value="Excluir">';
$paginacao->ButtonsHasRecords .= '<input type="button" name="export" id="export" value="Exportar para Excel">';

$paginacao->ButtonsHasNoRecords = '<input type="button" name="insert" id="insert" value="Incluir">';

#Conecta na base de dados
$paginacao->db = Database::getInstance();

$retorno = $paginacao->load( $app, $filterField, $orderField, $desc, $filterText, $orderField, $desc, $qtd_por_pagina, $offset, $currentPage );

echo $retorno;

#Query principal
$query  = " select ";

$query .= " u.id_usuario, ";
$query .= " initcap(lower(u.nome)) as nome, ";
$query .= " substr(u.cpf, 1, 3) || '.' || substr(u.cpf, 4, 3) || '.' || substr(u.cpf, 7, 3) || '-' || substr(u.cpf, 10, 2) as cpf, ";
$query .= " initcap(lower(u.nome_completo)) as nome_completo, ";
$query .= " u.email, ";
$query .= " u.ddd, ";
$query .= " substr(u.celular, 1, 3) || '-' || substr(u.celular, 4, 3) || '-' || substr(u.celular, 7, 3) as celular, ";
$query .= " p.nome as perfil ";

$query .= " from usuarios u ";
$query .= " inner join perfis p on p.id_perfil = u.id_perfil ";
$query .= " where id_usuario != 0 and u.ativo = TRUE ";

$thisAction = 'users';
$titulo = "Usuários";
$js_file = "javascript/users.js";
$idField = 'id_usuario';

$aFilterField = array( "lower(u.nome)", "lower(p.nome)",  "cpf", "lower(u.nome_completo)", "lower(email)",  "ddd", "celular" );
$aFilterArray = array( "Nome",                 "Perfil",  "CPF",          "Nome Completo",       "E-mail",  "DDD", "Celular");
$aLabelArray  = array( "Nome",                 "Perfil",  "CPF",          "Nome Completo",       "E-mail",  "DDD", "Celular");
$aQueryArray  = array( "nome",                 "perfil",  "cpf",          "nome_completo",        "email",  "ddd", "celular");
$widths =      array( 30, 100,                      100,    100,                      250,            200,     40,       80);
$alignments = array ("center",          "left", "left",                   "left",         "left", "left", "center", "left");

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

	<fieldset id="large">

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
			echo '<span><input type="text" name="filtertext" id="filtertext" class="medium_field" value="'.$filterText.'" onChange="javascript:unaccent(this);"></span>';
			echo '<span><input type="button" name="filterbutton" id="filterbutton" value="Filtrar"></span>';
			echo '</div>';
			#--- Final do filtro ---#
		} else {
			echo "<br>";
		}

		#---- Inicio do filtro ----#
		if (!empty($filterText)) {
			$query .= " and unaccent(".$aFilterField[$filterField-1].") ilike '%".$filterText."%' ";
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
							echo '   width="'.$widths[$contador].'"';
							echo '	align="'.$alignments[$contador].'" ';
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
						$counter = 1;
						foreach ($aQueryArray as $campo) {
							$html .= '<td align="'.$alignments[$counter++].'">'. $row[ $campo ] .'</td>';
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

		$_SESSION['query'] = $query.$orderBy;
		$_SESSION['labels'] = $aLabelArray;
		$_SESSION['fields'] = $aQueryArray;
		$_SESSION['filename'] = $titulo;
		$_SESSION['widths'] = $widths;
		$_SESSION['alignments'] = $alignments;

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
				echo '<input type="button" name="export" id="export" value="Exportar para Excel">';
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