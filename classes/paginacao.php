<?
class Paginacao {
	public	
		$db = NULL,
		$query = NULL,
		$thisAction = NULL,
		$titulo = NULL,
		$js_file = NULL,
		$idField = NULL,
		$aFilterField = NULL,
		$aFilterArray = NULL,
		$aLabelArray = NULL,
		$aQueryArray = NULL,
		$widths = NULL,
		$alignments = NULL,
		$ButtonsHasRecords = NULL,
		$ButtonsHasNoRecords = NULL;
	public function load( $app, $filterField, $orderField, $desc, $filterText, $orderField, $desc, $qtd_por_pagina, $offset, $currentPage ) {

		#variavel para controle do html de saida
		$retorno = "";

		#Executa query
		$this->db->setQuery($this->query. '  limit 1 ');

		#Verificando se a tabela tem registros      
		$this->db->execute();

		#Se tem ao menos um registro
		if ($this->db->getRows() > 0) {
			$tableHasRecords = true;
		} else {
			$tableHasRecords = false;
		}
		$retorno .= '<script type="text/javascript" src="'. $this->js_file .'"></script>';
		$retorno .= '<form name="'. $this->thisAction .'" id="'. $this->thisAction .'" method="post">';
		$retorno .= '<input type="hidden" name="id_perfil" id="id_perfil" value="'. $_SESSION['id_perfil'] .'">';
		$retorno .= '<input type="hidden" name="id_usuario" id="id_usuario" value="'. $_SESSION['id_usuario'] .'">';
		$retorno .= '<fieldset id="large">';
		$retorno .= '<legend>'. $this->titulo .'</legend>';
		if ($tableHasRecords) {
			#--- Inicio do filtro ---#
			$drop = new Dropdown();
			$select = $drop->getHTMLFromArray($this->aFilterArray, $filterField, 'filterfield', FALSE);
			$retorno .= '<div id="filtercontainer">';
			$retorno .= '<input type="hidden" name="orderfield" id="orderfield" value="'.$orderField.'">';
			$retorno .= '<input type="hidden" name="desc" id="desc" value="'.$desc.'">';
			$retorno .= '<span id="filterlabel">'.$select.'</span>';
			$retorno .= '<span><input type="text" name="filtertext" id="filtertext" class="medium_field" value="'.$filterText.'" onChange="javascript:unaccent(this);"></span>';
			$retorno .= '<span><input type="button" name="filterbutton" id="filterbutton" value="Filtrar"></span>';
			$retorno .= '</div>';
			#--- Final do filtro ---#
		} else {
			$retorno .= "<br>";
		}

		#---- Inicio do filtro ----#
		if (!empty($filterText)) {
			$this->query .= " and unaccent(".$this->aFilterField[$filterField-1].") ilike '%".$filterText."%' ";
		}
		#---- Fim do filtro ---- #

		#---- Início do order by ---#
		$orderBy = " order by ".$this->aFilterField[$orderField-1];
		$orderBy .= ($desc === 'true' ? " DESC " : " ASC ");
		#--- Fim do order by ---#

		#--- Início do limit/offset ---#
		$limitOffset = " limit ". $qtd_por_pagina ." offset ".$offset.";";
		#--- Fim do limit/offset ---#

		#Executa query
		$this->db->setQuery($this->query.$orderBy.$limitOffset);
		$this->db->execute();

		$result = $this->db->getResultSet();

		$html = "";

		if (sizeof($result) <= 0) {
			$retorno .= '<table cellspacing="1" cellpadding="0" width="100%" border="0">';
			$retorno .= '<thead><tr><th>&nbsp;</th></tr></thead>';
			$retorno .= '<tbody>';
			$retorno .= '<tr><td id="noRecords">Nenhum registro encontrado'. ($tableHasRecords ? ' para o filtro atual.' : '.') .'</td></tr>';
			$retorno .= '</tbody>';
			$retorno .= '</table>';
		} else {
			$retorno .= '<table cellspacing="1" cellpadding="0" width="100%" border="0">';
			$retorno .= '<thead>';
			$retorno .= '<tr>';
			$retorno .= '<th id="radioheader">&nbsp;</th>';
			$contador = 1;
			foreach ($this->aLabelArray as $label) {
				$retorno .= '<th ';
				$retorno .= '   width="'.$this->widths[$contador].'"';
				$retorno .= '	align="'.$this->alignments[$contador].'" ';
				$retorno .= '	class="order" ';
				$retorno .= '	onClick="changeOrderBy('. $contador .', '. $orderField. ', '. $filterField .", '". $filterText. "', ".$desc .');" ';
				$retorno .= '>';
				$retorno .= $label;
				$retorno .= '&nbsp;&nbsp;';
				$retorno .= arrow($orderField, $contador, $desc);
				$retorno .= '</th>';
				$contador++;
			}
			$retorno .= '</tr>';
			$retorno .= '</thead>';
			$retorno .= '<tbody>';
			foreach ($result as $row) {
				$html .= '<tr class="tableRow">';
				$html .= '<td align="center" width="20"><input type="radio" name="'. $this->idField .'" value="'.$row[ $this->idField ].'"></td>';
				$counter = 1;
				foreach ($this->aQueryArray as $campo) {
					$html .= '<td align="'.$this->alignments[$counter++].'">'. $row[ $campo ] .'</td>';
				}
				$html .= '</tr>';
			}
			$retorno .= $html;
			$retorno .= '</tbody>';
			$retorno .= '</table>';
		}

		#Executa query de totalização para paginação, com a mesma query e filtros executados anteriormente
		$this->db->setQuery($this->query);
		$this->db->execute();

		$_SESSION['query'] = $this->query.$orderBy;
		$_SESSION['labels'] = $this->aLabelArray;
		$_SESSION['fields'] = $this->aQueryArray;
		$_SESSION['filename'] = $this->titulo;
		$_SESSION['widths'] = $this->widths;
		$_SESSION['alignments'] = $this->alignments;

		$totalRecords = $this->db->getRows();

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

		$retorno .= '<div id="pagination">';

		if ($totalPages > 1) {

			$retorno .= '<span style="float:left;">Mostrando <b>'.number_format($totalRecords, 0, ',', '.').'</b> registros divididos em <b>'.number_format($totalPages, 0, ',', '.').'</b> páginas.</span>';

			if ($currentPage > 1) {
				$retorno .= '<span id="firstPage" title="Primeira página" onClick="javascript:newPage(1,'.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">&nbsp;</span>';
				$retorno .= '<span id="previousPage" title="Página anterior" onClick="javascript:newPage('.($currentPage-1).','.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">&nbsp;</span>';
			}

			for ($i = $rangeStart; $i <= $rangeFinish; $i++ ) {
				if ($i == $currentPage) {
					$retorno .= '<span class="page" title="Página atual" id="current">'.$i.'</span>';
				} else {
					$retorno .= '<span class="page" title="Página '.$i.'" onClick="javascript:newPage('.$i.','.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">'.$i.'</span>';
				}
			}

			if ($currentPage < $totalPages) {
				$retorno .= '<span id="nextPage" title="Próxima página" onClick="javascript:newPage('.($currentPage+1).','.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">&nbsp;</span>';
				$retorno .= '<span id="lastPage" title="Última página" onClick="javascript:newPage('.$totalPages.','.$filterField.','.chr(39).$filterText.chr(39).','.chr(39).$desc.chr(39).');">&nbsp;</span>';
			}

		} else {

			$retorno .= '<span style="float:left;">Mostrando <b>'.number_format($totalRecords, 0, ',', '.').'</b> registros.</span>';

		}
		$retorno .= '</div>';

		$retorno .= '<div id="buttons">';
			if ($tableHasRecords) {
				$retorno .= $this->ButtonsHasRecords;
			} else {
				$retorno .= $this->ButtonsHasNoRecords;
			}
		$retorno .= '</div>';
		$retorno .= '</fieldset>';
		$retorno .= '</form>';


		$retorno .= '<script language="javascript">';
		$retorno .= 'var thisAction = "'. $this->thisAction. '";';
		$retorno .= 'var idField = "'. $this->idField. '";';

		$retorno .= 'function newPage(currentPage, filterField, filterText, desc) {';
		$retorno .= "		location.href = 'home.php?action=' + thisAction + '&page=' + currentPage + '&filterfield=' + filterField + '&filtertext=' + filterText + '&desc=' + desc;";
		$retorno .= '}';

		$retorno .= 'function changeOrderBy(newOrder, oldOrder, filterfield, filter, desc) {';
		$retorno .= '	if (newOrder == oldOrder) {';
		$retorno .= '		desc = !desc;';
		$retorno .= '	} else {';
		$retorno .= '		desc = "false";';
		$retorno .= '	}';
		$retorno .= "	location.href = 'home.php?action=' + thisAction + '&orderfield=' + newOrder + '&filterfield=' + filterfield + '&filtertext=' + filter + '&desc=' + desc;";
		$retorno .= '}';


		$retorno .= '$(document).ready(function() {';

		$retorno .= "	$('#filtertext').focus();";

		$retorno .= "	$('form').on('submit', function(event) {";
		$retorno .= '		event.preventDefault();';
		$retorno .= '	});';

		$retorno .= '	$(document).keydown(function(e) {';
		$retorno .= '		e.stopPropagation();';
		$retorno .= '		if (e.keyCode === 13) {';
		$retorno .= '			$("#filterbutton").click();';
		$retorno .= '		}';
		$retorno .= '	});';

		$retorno .= '	$("#filterbutton").click(function() {';
		$retorno .= '		if (isEmpty($("#filtertext").val())) {';
		$retorno .= "			showMessage('Filtro inválido.<br>A carga de dados será feita sem nenhum filtro.', 'redirect', 'home.php?action=' + thisAction, 'long size');";
		$retorno .= '		} else {';
		$retorno .= '			var field = $("#filterfield option:selected").val();';
		$retorno .= '			var orderfield = $("#orderfield").val();';
		$retorno .= '			var text = $("#filtertext").val();';
		$retorno .= '			var desc = $("#desc").val();';
		$retorno .= "			location.href = 'home.php?action=' + thisAction + '&orderfield=' + orderfield + '&filterfield=' + field + '&filtertext=' + text + '&desc=' + desc;";
		$retorno .= '		}';
		$retorno .= '	});';

		$retorno .= '});';
		$retorno .= '</script>';

		return $retorno;
	}
}
?>