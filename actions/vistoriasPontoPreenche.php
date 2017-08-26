<?php

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', true);

$id_vistoria = $_REQUEST['id_vistoria'];
$id_ponto = $_REQUEST['id_ponto'];

include('../classes/XMLObject.php');
include('../classes/database.php');

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
$dia_semana = array("Domingo", "Segunda", "Terça" , "Quarta" , "Quinta", "Sexta", "Sábado");
$data_atual = date("d/m/Y");

	$query = "";
	$query .= " select pontos.*, ocorrencias.posicao from ocorrencias ";
	$query .= " inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
	$query .= " where id_vistoria = " . $id_vistoria;
	$query .= " order by posicao ";

	////echo $query;

	$db->setQuery($query);
	$db->execute();

	$result = $db->getResultSet();

	$total_pontos = $db->getRows();

?>

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
		<table>
		<tr>
			<td width="150px">&nbsp;</td>
			<td align="center"><label style="width:50px;height:20px;">Saída</label></td>
			<td align="center"><label style="width:50px;height:20px;">Chegada</label></td>
			<td align="center"><label style="width:50px;height:20px;">Rodados</label></td>
		</tr>
		<tr>
			<td align="right"><label>Kilometragem:</label></td>
			<td>&nbsp;<input type="text" name="km_saida" id="km_saida" class="small_field" style="width:100px;"/></td>
			<td>&nbsp;<input type="text" name="km_chegada" id="km_chegada" class="small_field" style="width:100px;"/></td>
			<td>&nbsp;<input type="text" name="km_rodados" id="km_rodados" class="small_field" style="width:100px;"/></td>
		</tr>
		<tr>
			<td align="right"><label>Hora:</label></td>
			<td>&nbsp;<input type="text" name="hs_saida" id="hs_saida" class="small_field" style="width:100px;"/></td>
			<td>&nbsp;<input type="text" name="hs_chegada" id="hs_chegada" class="small_field" style="width:100px;"/></td>
			<td>&nbsp;<input type="text" name="hs_rodados" id="hs_rodados" class="small_field" style="width:100px;"/></td>
		</tr>
		</table>
	</div> 


<br/>

<table width="100%">
<tr>
	<td><b>Seq</b></td>
	<td><b>Simak</b></td>
	<td><b>Endere&ccedil;o</b></td>
	
<?php if( $retorno['periodo'] == 'D'){ ?>
	<td align="center"><b>Calçada</b></td>
	<td align="center"><b>Cobertura</b></td>
	<td align="center"><b>Painel</b></td>
	<td><b>Observa&ccedil;&atilde;o</b></td>
<?php }else{ ?>
	<td align="center"><b>Painel</b></td>
	<td align="center"><b>Cobertura</b></td>
	<td><b>Observa&ccedil;&atilde;o</b></td>
<?php }?>
</tr>
<tr>
	<td colspan="10" style="border-bottom: 3px solid black;"></td>
</tr>
<?php
	foreach ($result as $row) {
?>
<tr>
	<td align="center" style="border-bottom: 1px solid black;"><?php echo $row['posicao'];?></td>
	<td align="center" style="border-bottom: 1px solid black;"><?php echo $row['codigo_abrigo'];?></td>
	<td style="border-bottom: 1px solid black;"><?php echo $row['endereco'];?></td>

<?php if( $retorno['periodo'] == 'D'){ ?>
	<td>
		<table>
			<tr>
			<td align="center" style="border: 1px solid black;"><input type="checkbox"/>PI</td>
			<td align="center" style="width:25px;border: 1px solid black;">RI</td>
			<td align="center" style="width:25px;border: 1px solid black;">FE</td>
			</tr>
		</table>
	</td>
	<td>
		<table>
			<tr>
			<td align="center" style="width:25px;border: 1px solid black;">PI</td>
			<td align="center" style="width:25px;border: 1px solid black;">RI</td>
			<td align="center" style="width:25px;border: 1px solid black;">FE</td>
			<td align="center" style="width:25px;border: 1px solid black;">MA</td>
			<td align="center" style="width:25px;border: 1px solid black;">SU</td>
			</tr>
		</table>
	</td>
	<td>
		<table>
			<tr>
			<td align="center" style="width:25px;border: 1px solid black;">PI</td>
			<td align="center" style="width:25px;border: 1px solid black;">RI</td>
			</tr>
		</table>
	</td>
	<td width="120px" style="border-bottom: 1px solid black;">&nbsp;</td>
<?php }else{ ?>
	<td>
		<table>
			<tr>
			<td align="center" style="width:25px;border: 1px solid black;">PI</td>
			<td align="center" style="width:25px;border: 1px solid black;">AP</td>
			<td align="center" style="width:25px;border: 1px solid black;">OK</td>
			<td align="center" style="width:25px;border: 1px solid black;">SO</td>
			</tr>
		</table>
	</td>
	<td>
		<table>
			<tr>
		<td align="center" style="width:40px;border: 1px solid black;">SUJA</td>
		<td align="center" style="width:40px;border: 1px solid black;">SOLTA</td>
			</tr>
		</table>
	</td>
	<td width="120px" style="border-bottom: 1px solid black;">&nbsp;</td>
<?php }?>
</tr>
<?php
	}
?>
</table>
	</fieldset>

</form>