<?php
$id_equipe = set($_REQUEST["id_equipe"], 0);


if ($id_equipe > 0) {

	#Conecta na base de dados
	$db = Database::getInstance();

	$query  = " select e.data_equipe, e.id_supervisor, u.nome as nome_supervisor ";
	$query .= " from equipes e ";
	$query .= " inner join usuarios u on e.id_supervisor = u.id_usuario ";
	$query .= " where u.ativo = TRUE ";
	$query .= " and e.id_equipe = ".$id_equipe;

}




?>

<script type="text/javascript" src="javascript/equipes_print.js"></script>

<fieldset id="medium">

	<legend>Impressão de Equipes</legend>

	<table cellspacing="1" cellpadding="0" width="100%" border="0">

		<thead>
			<tr>
				<th id="radioheader">&nbsp;</th>
				<th>Data</th>
				<th>Supervisor</th>
			</tr>
		</thead>

		<tbody>
			<?php
			$html = '';


			if (!empty($filterText)) {
				$aFilterField = array("e.data_equipe", "u.nome");
				$query .= " and ".$aFilterField[$filterField-1]." ilike '%".$filterText."%' ";
			}

			$query .= " order by e.data_equipe, u.nome; ";

			$db->setQuery($query);
			$db->execute();

			$result = $db->getResultSet();

			if (sizeof($result) <= 0) {
				$html .= '<tr>';
				$html .= '<td style="padding:8px" colspan="3">';
				$html .= 'Nenhum registro encontrado'.iif(empty($filterText),' para o filtro atual.','.');
				$html .= '</td>';
				$html .= '</tr>'.PHP_EOL;
			} else {
				foreach ($result as $row) {
					$html .= '<tr>';
					$html .= '<td align="center"><input type="radio" name="id_equipe" value="'.$row["id_equipe"].'"></td>';
					$html .= '<td>'.$row["data_equipe"].'</td>';
					$html .= '<td>'.$row["nome_supervisor"].'</td>';
					$html .= '</tr>'.PHP_EOL;
				}
			}
			echo $html;
			?>
		</tbody>
	</table>

	<div id="buttons">
		<input type="button" name="edit" id="edit" value="Editar">
		<input type="button" name="print" id="print" value="Imprimir">
		<input type="button" name="generate" id="generate" value="Gerar Novas">
		<input type="button" name="delete" id="delete" value="Excluir">
	</div>

</fieldset>
