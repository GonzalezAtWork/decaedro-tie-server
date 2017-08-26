<style>
#conteudo {
	padding-top:20px;
}
#botoes {
	padding-top:10px;
	text-align:center;
}
.labelEscopo {
	inline-block;
	width:20px;
	margin:12px 8px 0px 0px;
	text-align:left;
}
</style>
<?php
#Query principal
$query  = " select vistorias.*, eq.equipe, rt.roteiro, vis.qtd_pontos from vistorias ";

$query .= " left join ( ";
$query .= "		select id_vistoria, string_agg(nome,', ') as equipe from vistoriasEquipes ";
$query .= "			inner join usuarios on usuarios.id_usuario = vistoriasEquipes.id_equipe ";
$query .= "		group by id_vistoria ";
$query .= " ) as eq on vistorias.id_vistoria = eq.id_vistoria";

$query .= " left join ( ";
$query .= "		select id_vistoria, string_agg(nome,', ') as roteiro from vistoriasRoteiros ";
$query .= "			inner join roteiros on roteiros.id_roteiro = vistoriasRoteiros.id_roteiro ";
$query .= "		group by id_vistoria ";
$query .= " ) as rt on vistorias.id_vistoria = rt.id_vistoria";

$query .= " left join ( ";
$query .= "		select id_vistoria, sum(qtd_pontos) as qtd_pontos from vistoriasRoteiros group by id_vistoria ";
$query .= " ) as vis on vistorias.id_vistoria = vis.id_vistoria";
$query .= " where vistorias.ativo = true";
//$query .= " order by vistorias.executada, vistorias.data desc ";


$thisAction = "vistorias";
$titulo = "Vistorias";
$js_file = "javascript/vistorias.js";
$idField = "id_vistoria";

$aFilterField = array( "id_vistoria", "u.nome", "email", "ddd", "celular", "p.nome" );
$aFilterArray = array( "Código", "Data", "Período", "Encarregado", "Roteiros");
$aLabelArray  = array( "Código", "Agendada", "Executada", "Data", "Período", "Encarregado", "Roteiros", "Pontos" );

$widths = array(30, 70, 70, 70, 80, 60, 0, 130, 30);


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

	<fieldset id="groups">
	
		<legend>Vistorias</legend>

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

		echo "<br>".$query.$orderBy.$limitOffset."<br>";

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
			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
				<thead>
					<tr>
						<th id="radioheader">&nbsp;</th>
						<?php
						$contador = 1;
						foreach ($aLabelArray as $label) {
							echo '<th ';
							echo '   width="'.$widths[$contador].'"';
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
						?>
						<tr height="24" class="tableRow">
							<td class="table_cell" align="center"><input type="radio" name="id_vistoria" value="<?php echo $row["id_vistoria"];?>"></td>
							<td class="table_cell" align="center"><?php echo substr('000000' . $row["id_vistoria"], -5);?></td>
							<td class="table_cell" align="center"><img src="images/checkbox_<?php echo (($row["agendada"] == 't') ? '1' : '0');?>.png" width="13" height="13"></td>
							<td class="table_cell" align="center"><img src="images/checkbox_<?php echo (($row["executada"] == 't') ? '1' : '0');?>.png" width="13" height="13"></td>
							<td class="table_cell" align="center"><?php echo date("d/m/Y", strtotime($row["data"]));?></td>
							<td class="table_cell" align="center"><?php echo (($row["periodo"] == 'D') ? 'Diurno' : (($row["periodo"] == 'N') ? 'Noturno' : ''));?></td>
							<td class="table_cell"><?php echo $row["equipe"];?></td>
							<td class="table_cell"><?php echo $row["roteiro"];?></td>
							<td class="table_cell" align="center"><?php echo $row["qtd_pontos"];?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<?php
		}
		?>

		<div id="escopo">
			<label class="labelEscopo"><input type="checkbox" name="escopo[]" id="agendadas">&nbsp;Agendadas</label>
			<label class="labelEscopo"><input type="checkbox" name="escopo[]" id="executadas">&nbsp;Executadas</label>
			<label class="labelEscopo"><input type="checkbox" name="escopo[]" id="salvas">&nbsp;Novas</label>
		</div>

		<div id="pagination">
			<?php
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
			?>
		</div>

		<div id="buttons">
			<?php
			#Se tem permissão de super-usuário, pode incluir vistorias
			if (strpos($_SESSION["permissoes"], "inserirvistoria") >= 0) {
				?>
				<input type="button" name="new" id="new" value="Nova Vistoria">
				<?php
			}
			?>
			<input type="button" name="edit" id="edit" value="Visualizar">
		</div>

	</fieldset>

</form>