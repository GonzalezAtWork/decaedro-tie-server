<?php

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', true);

$id_vistoria = $_REQUEST['id_vistoria'];

$db = Database::getInstance();

	$query  = " select vistorias.*, eq.equipe, rt.roteiro, vis.qtd_pontos from vistorias ";
/*
	$query .= " left join ( ";
	$query .= "		select id_vistoria, string_agg(nome,', ') as equipe from vistoriasEquipes ";
	$query .= "			inner join equipes on equipes.id_equipe = vistoriasEquipes.id_equipe ";
	$query .= "		group by id_vistoria ";
	$query .= " ) as eq on vistorias.id_vistoria = eq.id_vistoria";
*/

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

	$query .= " where vistorias.id_vistoria = " . $id_vistoria;

	$query .= " order by vistorias.data ";

	////echo $query;

	$db->setQuery($query);
	$db->execute();


$retorno= $db->getResultSet();



if(is_array($retorno)){
	$retorno = $retorno[0];
}

//echo "CARAIO: " . $retorno['executada'];
if($retorno['executada'] == 't'){
	$disabled = ' disabled ';
}else{
	$disabled = '';
}

$dia_semana = array("Domingo", "Segunda", "Terça" , "Quarta" , "Quinta", "Sexta", "Sábado");
$data_atual = date("d/m/Y");

	$query = "";
	$query .= " select pontos.*, ocorrencias.*, foto.total, ocorrencias.dt_lastupdate from ocorrencias ";
	$query .= " inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
	$query .= " left join (select id_ocorrencia, count(1) as total from fotografias group by id_ocorrencia ) as foto on foto.id_ocorrencia = ocorrencias.id_ocorrencia ";
	$query .= " where id_vistoria = " . $id_vistoria;
	$query .= " order by ocorrencias.vistoriada desc, ocorrencias.dt_lastupdate desc, ocorrencias.posicao ";

	////echo $query;

	$db->setQuery($query);
	$db->execute();

	$result = $db->getResultSet();

	$total_pontos = $db->getRows();

?>

<script type="text/javascript" src="javascript/vistorias.js"></script>
<form name="form" id="form" method="post">

	<input type="hidden" name="id_vistoria" id="id_vistoria" value="<?php echo $id_vistoria;?>">

	<fieldset id="groups">
	<legend>Execução de Vistoria</legend>

	<div class="line">
		<label style="width:150px">Código:</label>
		<span class="field">&nbsp;<b><?php echo substr('000000' . $id_vistoria, -5);?></b></span>
	</div>
	<div class="line">
		<label style="width:150px">Data:</label>
		<span class="field">&nbsp;<?php echo date("d/m/Y", strtotime($retorno['data'])) ;?> - <?php echo $dia_semana[ date("w", strtotime($retorno['data'])) ];?></span>
	</div>
	<div class="line">
		<label style="width:150px">Roteiro:</label>
		<span class="field">&nbsp;<b><?php echo strtoupper($retorno['roteiro']);?></b> (<small><?php if( $retorno['periodo'] == 'D'){ ?>Diurno<?php }else{ ?>Noturno<?php } ?></small>) - <b><?php echo$total_pontos;?></b> Pontos de Parada</span>
	</div>
	<div class="line">
		<label style="width:150px">Encarregados:</label>
		<span class="field">&nbsp;<?php echo $retorno['equipe'];?></span>
	</div> 
	
	<div>
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="150px">&nbsp;</td>
			<td align="center"><label style="width:50px;height:20px;">Saída</label></td>
			<td align="center"><label style="width:50px;height:20px;">Chegada</label></td>
			<td align="center"><label style="width:50px;height:20px;">Rodados</label></td>
		</tr>
		<tr>
			<td align="right"><label>Kilometragem:</label></td>
			<td>&nbsp;<input <?php echo $disabled;?>  type="text" name="km_saida" id="km_saida" class="small_field" style="width:100px;" value="<?php echo $retorno['km_saida'];?>"/></td>
			<td>&nbsp;<input <?php echo $disabled;?>  type="text" name="km_chegada" id="km_chegada" class="small_field" style="width:100px;" value="<?php echo $retorno['km_chegada'];?>"/></td>
			<td>&nbsp;<input <?php echo $disabled;?>  type="text" name="km_rodados" id="km_rodados" class="small_field" style="width:100px;" value="<?php echo $retorno['km_rodados'];?>"/></td>
		</tr>
		<tr>
			<td align="right"><label>Hora:</label></td>
			<td>&nbsp;<input <?php echo $disabled;?>  type="text" name="hs_saida" id="hs_saida" class="small_field" style="width:100px;" value="<?php echo $retorno['hs_saida'];?>"/></td>
			<td>&nbsp;<input <?php echo $disabled;?>  type="text" name="hs_chegada" id="hs_chegada" class="small_field" style="width:100px;" value="<?php echo $retorno['hs_chegada'];?>"/></td>
			<td>&nbsp;<input <?php echo $disabled;?>  type="text" name="hs_rodados" id="hs_rodados" class="small_field" style="width:100px;" value="<?php echo $retorno['hs_rodados'];?>"/></td>
		</tr>
		</table>
	</div> 
<br/>
<div style="padding-top:20px;">

			<table  border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#005599">
<tr>
	<td class="table_title" width="50px"><b>&nbsp;</b></td>
	<td class="table_title" width="50px"><b>Gerar</b></td>
	<td class="table_title" width="50px"><b>Seq</b></td>
	<td class="table_title" width="50px"><b>Simak</b></td>
	<td class="table_title"><b>Endere&ccedil;o</b></td>
	

<?php

	$query  = " select vistoriasItens.*, itensTipo.nome as tipo from vistoriasItens  ";
	$query .= " inner join itensTipo on vistoriasItens.id_tipoitem = itensTipo.id_tipoitem  ";
	$query .= " where vistoriasItens.id_tipoitem != 0 and vistoriasItens.id_tipoitem != 1  ";
	$query .= " order by vistoriasItens.id_tipoitem, vistoriasItens.id_item ";
	$db->setQuery($query);
	$db->execute();
	$itens_result = $db->getResultSet();
	$old = "";
	foreach ($itens_result as $tipoitem) {
		if($old != $tipoitem['tipo']){
			echo '<td class="table_title" align="center" width="50px"><b>'. $tipoitem['tipo'] .'</b></td>';
			$old = $tipoitem['tipo'];
		}
	}
?>

	<td class="table_title"><b>Obs.</b></td>
	<td class="table_title"><b>Fotos</b></td>
	<td class="table_title"><b>Hora</b></td>

</tr>
<?php
	foreach ($result as $row) {
		$itensVistoria = explode(',',$row['itensvistoria']);
?>
<tr height="24" style="padding:4px">
	<td bgcolor="#FFFFFF" align="center"><input <?php echo $disabled;?>  <?php echo ($row['vistoriada'] == 't')?' selected checked ':'';?> type="checkbox" name="pontos[]" id="pontos[]" value="<?php echo $row['id_ponto'];?>"/></td>
	<td bgcolor="#FFFFFF" align="center"><input <?php echo $disabled;?>  <?php echo ($row['gerar_os'] == 't')?' selected checked ':'';?> type="checkbox" name="gerar_os[]" id="gerar_os[]" value="<?php echo $row['id_ponto'];?>"/></td>
	<td bgcolor="#FFFFFF" align="center"><a href="home.php?action=ocorrencias_edit&id_ocorrencia=<?php echo $row['id_ocorrencia'];?>"><?php echo substr('000' .  $row['posicao'], -3);?></a></td>
	<td bgcolor="#FFFFFF" align="center"><?php echo $row['codigo_abrigo'];?></td>
	<td bgcolor="#FFFFFF">&nbsp;<?php echo $row['endereco'];?></td>

	<?php


	$old = "";
	echo '<td bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="0"><tr>';
	$cont = 0;
	foreach ($itens_result as $tipoitem) {
		$cont++;
		$resecho = "";
		if($cont == 1 ){
			$old = $tipoitem['tipo'];
		}
		if($cont != 1 && $old != $tipoitem['tipo']){
			$resecho .= '</tr></table></td>';
			$resecho .= '<td bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="0"><tr>';
			$old = $tipoitem['tipo'];
		}
		$resecho .= '<td align="center" style="width:50px;border: 0px solid black;">';
		$resecho .= '<input '. $disabled .' type="checkbox" '. ((array_search($tipoitem['id_item'],$itensVistoria) !== false)?' checked selected ':'').' name="itensVistoria_'. $row['id_ponto'] .'" id="itensVistoria_'. $row['id_ponto'] .'" value="'. $tipoitem['id_item'] .'" />';
		$resecho .= $tipoitem['sigla'];
		$resecho .= '</td>';
		echo $resecho;
	}
	echo '</tr></table></td>';
	?>

	<td bgcolor="#FFFFFF" width="30px">&nbsp;
	<?php
	if($row['observacaovistoria'] != ""){
		echo '<a href="javascript:alert(\''. $row['observacaovistoria']. '\')">Obs.</a>';
	}
	?>
	</td>
	<td bgcolor="#FFFFFF" width="30px" align="center"><?php echo $row['total'];?></td>
	<td bgcolor="#FFFFFF" width="20px">&nbsp;<?php if($row["vistoriada"] == 't') echo date("H:i", strtotime($row["dt_lastupdate"]));?></td>

</tr>
<?php
	}
?>
</table>
</div>

<?php if($disabled == ''){ ?>
	<div align="center" style="padding-top:10px;">
		<span><input type="button" name="save_execucao" id="save_execucao" value="Salvar"></span>
		<span><input type="button" name="close" id="close" value="Finalizar Vistoria"></span>
	</div>
<?php }; ?><br/>
	</fieldset>

</form>