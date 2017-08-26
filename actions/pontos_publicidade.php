<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$simak = (isset($_REQUEST['simak']))?$_REQUEST['simak']:"";
$id_ponto = (isset($_REQUEST['id_ponto']))?$_REQUEST['id_ponto']:"";

	$query = ' select * from pontos ';
	$query .= ' where ';

if($simak != ""){
		$query .= " 		pontos.codigo_abrigo = '". $simak ."' ";
}else{
		$query .= " 		pontos.id_ponto = ". $id_ponto ." ";
}

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

		$query  = " select  ";
		$query .= " 	id_ponto,  ";
		$query .= " 	nome,  ";
		$query .= " 	id_item,  ";
//		$query .= " 	(string_to_array(replace(nomeImagensPublicidade,id_item||',',''),';'))[1] as antiga,  ";
		$query .= " 	(string_to_array(replace(nomeImagensPublicidade,id_item||',',''),';'))[2] as atual ";
		$query .= " from ( ";
		$query .= " 	select dt_lastupdate, id_ponto, id_item, nome, unnest(string_to_array(nomeImagensPublicidade,'|')) as nomeimagenspublicidade from ( ";
		$query .= " 		select ocorrencias.dt_lastupdate, ocorrencias.id_ponto, vistoriasitens.nome, vistoriasitens.id_item, ocorrencias.nomeimagenspublicidade  from ocorrencias  ";
		$query .= " 			inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
		$query .= " 			inner join vistoriasitens on '|' || nomeImagensPublicidade || '|'  like '%|'|| id_item ||',%' ";
		$query .= " 			inner join ( ";
		$query .= " 				select max(dt_lastupdate) as dt_lastupdate, ocorrencias.id_ponto, vistoriasitens.id_item from ocorrencias  ";
		$query .= " 					inner join vistoriasitens on '|' || nomeImagensPublicidade || '|'  like '%|'|| id_item ||',%' ";
		$query .= " 				where  ";
		$query .= " 					executada = true and ";
		$query .= " 					dt_lastupdate is not null ";
		$query .= " 				group by ocorrencias.id_ponto, vistoriasitens.id_item ";
		$query .= " 			) as controle on ocorrencias.dt_lastupdate = controle.dt_lastupdate and ocorrencias.id_ponto = controle.id_ponto and vistoriasitens.id_item = controle.id_item ";
		$query .= " 		where  ";
if($simak != ""){
		$query .= " 		pontos.codigo_abrigo = '". $simak ."' ";
}else{
		$query .= " 		ocorrencias.id_ponto = ". $id_ponto ." ";
}
		$query .= " 	) as base1 ";
		$query .= " ) as base2 ";
		$query .= " where '|' || nomeImagensPublicidade || '|'  like '%|'|| id_item ||'%' ";
		$query .= " order by id_ponto ";

		//echo $query;
		$db->setQuery($query);
		$db->execute();
		$db_ocorrencia = $db->getResultSet();

		echo "<table width='100%'><tr>";
		foreach ($db_ocorrencia as $row) {
					echo "<td valign='top'>";
					echo "<table width='100%'>";
					echo "<tr>";
					echo "<td colspan='2' align='center'>". $row['nome'] ."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td align='center'>Imagem: ". $row['atual'] ."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td align='center'><img width='200' src='imagem.php?nome=". base64_encode( $row["atual"] )."'/></td>";
					echo "</tr>";
					echo "</table>";
					echo "</td>";
		}	
		echo "</tr></table>";
?>

		<div align="center" style="padding-top:10px;">
			<input type="button" value="Dados do Ponto" onclick="location.href='home.php?action=pontos_edit&id_ponto=<?php echo $id_ponto;?>'"/>
		</div>
	</fieldset>
</form>