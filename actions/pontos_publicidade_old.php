<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_ponto = (isset($_REQUEST['id_ponto']))?$_REQUEST['id_ponto']:0;

	$query = ' select * from pontos ';
	$query .= ' where pontos.id_ponto = '. $id_ponto;

	$db = Database::getInstance();
	$db->setQuery($query);
	$db->execute();
	$db_result = $db->getResultAsObject();

?>

<script type="text/javascript" src="javascript/pontos.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<form name="form" id="form" method="post">

	<input type="hidden" name="id_ponto" id="id_ponto" value="<?php echo $db_result->id_ponto; ?>">

	<fieldset>
		<legend id="titulo">Publicidade no Ponto de Parada</legend>
		<div class="line" align="center">
			<span class="field" align="center">
				<b>Simak:</b> <?php echo $db_result->codigo_abrigo;?> 
				<b>Endereço:</b> <?php echo $db_result->endereco;?>
			</span>
		</div>

<?php

		$query  = "";
		$query .= " select ";
		$query .= " 	vistoriasItens.id_item, vistoriasItens.nome ";
		$query .= " from vistoriasItens ";
		$query .= " where vistoriasItens.id_tipoitem = 1 ";

		//echo $query;
		$db->setQuery($query);
		$db->execute();
		$db_itens = $db->getResultSet();


		echo "<table width='100%'><tr>";
		foreach ($db_itens as $item) {
			$query  = "";
			$query .= " select * ";
			$query .= " from ocorrencias ";
			$query .= " where id_ponto = " . $id_ponto;
			$query .= " and '|' || nomeImagensPublicidade ilike '%|". $item['id_item'] .",%' ";
			$query .= " and executada = true ";
			$query .= " order by dt_lastupdate desc, data desc ";
			$query .= " limit 1 ";

			//echo $query;
			$db->setQuery($query);
			$db->execute();
			$db_ocorrencia = $db->getResultSet();
			if($db->getRows() == 0){
				echo "<td>";
				echo "<table width='100%'>";
				echo "<tr>";
				echo "<td colspan='2' align='center'>". $item['nome'] ."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td align='center'>Imagem: Sem Imagem</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td align='center'><img width='200' src='data: image/jpeg;base64,'/></td>";
				echo "</tr>";
				echo "</table>";
				echo "</td>";
			}


			foreach ($db_ocorrencia as $row) {
				$trocas = explode("|", $row['nomeimagenspublicidade']);
				foreach($trocas as $troca){
					//51,brahma velha;brahma nova
					$codigo = explode(",",$troca);
					$antiga = explode(";", $codigo[1]);
					$nova   = $antiga[1];
					$antiga = $antiga[0];
					$codigo = $codigo[0];
					if( $codigo == $item['id_item']){
						$query  = "";
						$query .= " select ";
						$query .= " 	vistoriasItens.nome,  ";
						$query .= " 	antiga.imagem as imagem_antiga,  ";
						$query .= " 	antiga.nome as nome_antiga,  ";
						$query .= " 	nova.imagem as imagem_nova,  ";
						$query .= " 	nova.nome as nome_nova  ";
						$query .= " from vistoriasItens ";
						$query .= " left join (select ". $codigo ." as id_item, nome, imagem from publicidadeimagens where nome = '". $antiga ."') as antiga on vistoriasItens.id_item = antiga.id_item ";
						$query .= " left join (select ". $codigo ." as id_item, nome, imagem from publicidadeimagens where nome = '". $nova ."') as nova on vistoriasItens.id_item = nova.id_item ";
						$query .= " where vistoriasItens.id_item = ". $codigo ." ";

						//echo $query;
						$db->setQuery($query);
						$db->execute();
						$db_troca = $db->getResultAsObject();

						echo "<td>";
						echo "<table width='100%'>";
						echo "<tr>";
						echo "<td colspan='2' align='center'>". $db_troca->nome ."</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td align='center'>Imagem: ". $db_troca->nome_nova ."</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td align='center'><img width='200' src='data: image/jpeg;base64,". $db_troca->imagem_nova ."'/></td>";
						echo "</tr>";
						echo "</table>";
						echo "</td>";
					}
				}
			}	
		}
		echo "</tr></table>";
?>

		<div align="center" style="padding-top:10px;">
			<input type="button" value="Dados do Ponto" onclick="location.href='home.php?action=pontos_edit&id_ponto=<?php echo $id_ponto;?>'"/>
		</div>
	</fieldset>
</form>