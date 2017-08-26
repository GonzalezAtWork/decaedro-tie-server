<?php
#Se não tem campo de filtro, passa nome como default
$filterField = set((empty($_REQUEST['filterfield']) ? 1 : $_REQUEST['filterfield']), '');

#Se não tem texto de filtro, passa string em branco
$filterText = set((empty($_REQUEST['filtertext']) ? '' : $_REQUEST['filtertext']), '');

#Se não tem ordem de registros, passa ordem crescente (decrescente falso)
$desc = set((empty($_REQUEST['desc']) ? 'false' : $_REQUEST['desc']), 'false');

#Pega a página atual
$currentPage = set((empty($_REQUEST['page']) ? 1 : $_REQUEST['page']), 1);

#Primeiro registro da paginação
$offset = (($currentPage * $app->recordsToShow) - $app->recordsToShow);

#Verificando se a tabela tem registros      
$query  = " select id_veiculacao from publicidadeveiculacao ";
$query .= " where ativo = true limit 1 ";

#Conecta na base de dados
$db = Database::getInstance();

#Executa query
$db->setQuery($query);
$db->execute();

#Se tem ao menos um registro
if ($db->getRows() > 0) {
	$tableHasRecords = true;
} else {
	$tableHasRecords = false;
}

?>

<script type="text/javascript" src="javascript/publicidadeVeiculacao.js"></script>

<form name="veiculacao" id="veiculacao" method="post">

	<fieldset>

		<legend>Roteiro de Veiculação de Publicidade</legend>

		<?php
		if ($tableHasRecords) {
			#--- Inicio do filtro ---#
			$drop = new Dropdown();
			$select = $drop->getHTMLFromArray(array("Simak", "Ótima", "Tipo", "Bairro", "Localização", "Semana"), $filterField, 'filterfield', FALSE);
			echo '<div id="filtercontainer">';
			echo '<input type="hidden" name="desc" id="desc" value="'.$desc.'">';
			echo '<span id="filterlabel">'.$select.'</span>';
			echo '<span><input type="text" name="filtertext" id="filtertext" class="medium_field" value="'.$filterText.'"></span>';
			echo '<span><input type="button" name="filterbutton" id="filterbutton" value="Filtrar"></span>';
			echo '</div>';
			#--- Final do filtro ---#
		} else {
			echo "<br>";
		}

		#Query principal
		$query  = " select ";
		$query .= "		distinct v.id_veiculacao, ";
		$query .= "		CAST(coalesce(v.simak, '0') AS integer) as simak, ";
		$query .= "		p.codigo_novo as otima, ";
		$query .= "		pt.nome as tipo,  ";
		$query .= "		b.nome as bairro,  ";
		$query .= "		p.endereco,  ";
		$query .= "		v.semana, ";
		$query .= "		v.ano,  ";
		$query .= "		v.caixa,  ";
		$query .= "		v.face,  ";
		$query .= "		aux.nome_imagem as nome_imagem_atual,  ";
		$query .= "		v.nome_imagem as nome_imagem_nova,  ";
		$query .= "		i.nome as imagem_nova,  ";
		$query .= "		iaux.nome as imagem_atual ";
		$query .= " from publicidadeveiculacao v ";

		$query .= " left join publicidadeveiculacao aux on (";
		$query .= "		aux.simak = v.simak and ";
		$query .= "		aux.caixa = v.caixa and ";
		$query .= "		aux.face = v.face and ";
		$query .= "		aux.semana = (case when (v.semana - 1) = 0 then 52 else (v.semana - 1) end) and ";
		$query .= "		aux.ano = (case when (v.semana - 1) = 0 then (v.ano-1) else v.ano end) and";
		$query .= "		aux.ativo = false and ";
		$query .= "		aux.id_veiculacao in ( ";
		$query .= "			select id_veiculacao from (";
		$query .= "				select max(id_veiculacao) as id_veiculacao, simak";
		$query .= "				from publicidadeveiculacao";
		$query .= "				where ativo = false";
		$query .= "				group by simak, caixa, face";
		$query .= "			) ";
		$query .= "			as t) ";
		$query .= " ) ";

		$query .= " left join pontos p on (v.simak = p.codigo_abrigo) ";
		$query .= " left join bairros b on (p.id_bairro = b.id_bairro) ";
		$query .= " left join pontospadrao pp on (p.id_padrao = pp.id_padrao) ";
		$query .= " left join pontostipo pt on (pp.id_tipo = pt.id_tipo) ";
		$query .= " left join publicidadeimagens i on (trim(v.nome_imagem) = trim(i.nome)) ";
		$query .= " left join publicidadeimagens iaux on (trim(aux.nome_imagem) = trim(iaux.nome)) ";

		$query .= " where v.ativo = true ";

		#---- Inicio do filtro ----#
		$aFilterField = array("CAST(coalesce(v.simak, '0') AS integer)", "p.codigo_novo", "pt.nome", "b.nome", "p.endereco", "v.semana");
		if (!empty($filterText)) {
			$query .= " and ".$aFilterField[$filterField-1]." ilike '%".$filterText."%' ";
		}
		#---- Fim do filtro ---- #

		#---- Início do order by ---#
		$orderBy = " order by ".$aFilterField[$filterField-1];
		$orderBy .= ($desc === 'true' ? " DESC " : " ASC ");
		if ($filterField != 1) {
			$orderBy .= ", CAST(coalesce(v.simak, '0') AS integer) ASC ";
		}
		#--- Fim do order by ---#

		#--- Início do limit/offset ---#
		$limitOffset = " limit ".$app->recordsToShow." offset ".$offset.";";
		#--- Fim do limit/offset ---#

		#Executa query
		$db->setQuery($query.$orderBy.$limitOffset);
		$db->execute();

		$result = $db->getResultSet();

		$html = "";

		if (sizeof($result) <= 0) {
			?>
			<table cellspacing="1" cellpadding="0" width="100%" border="0">
				<thead>
					<tr>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td id="noRecords">Nenhum registro encontrado<?php echo ($tableHasRecords ? ' para o filtro atual.' : '.');?></td>
					</tr>
				</tbody>
			</table>
			<?php
		} else {
			?>
			<table cellspacing="1" cellpadding="0" width="100%" border="0">
				<thead>
					<tr>
						<th id="radioheader">&nbsp;</th>
						<th align="center" class="order" onClick="changeOrderBy(1, <?php echo $filterField.", '".$filterText."', ".$desc;?>);">Simak&nbsp;&nbsp;<?php echo arrow($filterField, 1, $desc);?></th>
						<th align="center" class="order" onClick="changeOrderBy(2, <?php echo $filterField.", '".$filterText."', ".$desc;?>);">Ótima&nbsp;&nbsp;<?php echo arrow($filterField, 2, $desc);?></th>
						<th align="center" class="order" onClick="changeOrderBy(3, <?php echo $filterField.", '".$filterText."', ".$desc;?>);">Tipo&nbsp;&nbsp;<?php echo arrow($filterField, 3, $desc);?></th>
						<th align="center" class="order" onClick="changeOrderBy(4, <?php echo $filterField.", '".$filterText."', ".$desc;?>);">Bairro&nbsp;&nbsp;<?php echo arrow($filterField, 4, $desc);?></th>
						<th align="left"   class="order" onClick="changeOrderBy(5, <?php echo $filterField.", '".$filterText."', ".$desc;?>);">Localização&nbsp;&nbsp;<?php echo arrow($filterField, 5, $desc);?></th>
						<th align="center" class="order" onClick="changeOrderBy(6, <?php echo $filterField.", '".$filterText."', ".$desc;?>);" width="2">Semana&nbsp;&nbsp;<?php echo arrow($filterField, 6, $desc);?></th>
						<th align="center" width="2">Caixa<br>Face</th>
						<th align="center" width="2">Imagem<br>Atual</th>
						<th align="center" width="2">Nova<br>Imagem</th>
					</tr>
				</thead>

				<tbody>
					<?php
					foreach ($result as $row) {

						$html .= '<tr class="tableRow">';
						$html .= '<td align="center" width="20"><input type="radio" name="id_veiculacao" value="'.$row["id_veiculacao"].'"></td>';
						$html .= '<td align="center" width="100">'.$row["simak"].'</td>';
						$html .= '<td align="center" width="100">'.$row["otima"].'</td>';
						$html .= '<td align="center" width="100">'.$row["tipo"].'</td>';
						$html .= '<td align="left"   width="100">'.$row["bairro"].'</td>';
						$html .= '<td>'.$row["endereco"].'</td>';
						$html .= '<td align="center" width="100">'.$row["semana"].'</td>';
						$html .= '<td align="center">'.$row["caixa"].'<br>'.(strtolower($row["face"])=='i' ? 'Interna' : (strtolower($row["face"])=='e' ? 'Externa' : 'Erro')).'</td>';

						$html .= '<td align="center">';
						if (empty($row["imagem_atual"])) {
							$html .= 'Sem postagem';
						} else {
							$html .= '<img src="imagem.php?nome='. base64_encode( $row["imagem_atual"] ).'" width="50" align="middle" title="'.$row["nome_imagem_atual"].'">';
						}
						$html .= '</td>';

						$html .= '<td align="center">';
						if (empty($row["imagem_nova"]) || ($row["nome_imagem_atual"] == $row["nome_imagem_nova"])) {
							$html .= 'Sem postagem';
						} else {
							$html .= '<img src="imagem.php?nome='. base64_encode( $row["imagem_nova"] ).'" width="50" align="middle" title="'.$row["nome_imagem_nova"].'">';
						}
						$html .= '</td>';

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

		#Acerta páginas inicial e final
		$rangeStart = $currentPage - ceil($rangeLenght / 2);
		$rangeFinish = $rangeStart + ($rangeLenght - 1);

		#Corrige a página inicial
		if ($rangeStart < 1) {
			$rangeStart = 1;
			$rangeFinish = ceil($rangeLenght / 2) * 2;
		}

		#Corrige a página final
		if ($rangeFinish > $totalPages) {
			$rangeFinish = $totalPages;
			$rangeStart = $rangeFinish - $rangeLenght;
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

		<div id="buttons">
			<input type="button" name="import" id="import" value="Importar CSV">
			<?php
			if ($tableHasRecords) {
				echo '<input type="button" name="edit" id="edit" value="Editar">';
				echo '<input type="button" name="insert" id="insert" value="Incluir">';
				echo '<input type="button" name="delete" id="delete" value="Excluir">';
				echo '<input type="button" name="import" id="export" value="Gerar Ordem de Serviço">';
			} else {
				echo '<input type="button" name="insert" id="insert" value="Incluir">';
			}
			?>
		</div>

	</fieldset>

</form>