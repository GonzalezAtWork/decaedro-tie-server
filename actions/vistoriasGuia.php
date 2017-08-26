<?php

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', true);

$id_vistoria = $_REQUEST['id_vistoria'];

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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" data-cast-api-enabled="true">		
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Guia de Vistoria</title>
<style type="text/css">
	body {
	height: 842px;
	width: 700px;
	margin-left: auto;
	margin-right: auto;
}
.c4{
	font-size:14pt;
	font-style:italic;
}
p{
	color:#000000;
	font-size:10pt;
	margin:0;
	font-family:"Arial";
}
td{
	color:#000000;
	font-size:8pt;
	margin:0;
	font-family:"Arial";
} 
 
</style>
</head>
<body>
	<p align="center"><span class="c4">Guia de Vistoria para Roteiro <b><u><?php echo strtoupper($retorno['roteiro']);?></u></b></span></p>
	<p align="right"><b>Código:</b> <?php echo substr('000000' . $id_vistoria, -5);?></p>
<br/>
<table width="100%" border="0">
<tr>
	<td><span style="display:inline-block; width:80px"><b>Data:</b></span> <?php echo date("d/m/Y", strtotime($retorno['data'])) ;?> - <?php echo $dia_semana[ date("w", strtotime($retorno['data'])) ];?></td>
	<td><span style="display:inline-block; width:100px"><b>Encarregados:</b></span> <?php echo $retorno['equipe'];?></td>
	<td><span style="display:inline-block; width:100px"><b>Roteiro:</b></span> <b><u><?php echo strtoupper($retorno['roteiro']);?></u></b></td>
</tr>
<tr>
	<td><span style="display:inline-block; width:80px"><b>Km de Sa&iacute;da:</b></span> _________________</td>
	<td><span style="display:inline-block; width:100px"><b>Km de Chegada:</b></span> _______________________</td>
	<td><span style="display:inline-block; width:100px"><b>Km Rodados:</b></span> ____________________</td>
</tr>
<tr>
	<td><span style="display:inline-block; width:80px"><b>Hora de Sa&iacute;da:</b></span> _________________</td>
	<td><span style="display:inline-block; width:100px"><b>Hora de Chegada:</b></span> _______________________</td>
	<td><span style="display:inline-block; width:100px"><b>Horas Rodadas:</b></span> ____________________</td>
</tr>
<?php
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
<tr>
	<td colspan="10" align="center"><br/>Total: <b><?php echo$total_pontos;?></b> Pontos de Parada &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; Emitida em <b><?php echo $data_atual;?></b></td>
</tr>
</table>
<br/>

<table width="100%">
<tr>
	<td><b>Seq</b></td>
	<td><b>Simak</b></td>
	<td><b>Endere&ccedil;o</b></td>
<?php
	$query  = " select vistoriasItens.*, itensTipo.nome as tipo from vistoriasItens  ";
	$query .= " inner join itensTipo on vistoriasItens.id_tipoitem = itensTipo.id_tipoitem  ";
	$query .= " where vistoriasItens.id_tipoitem != 0 and vistoriasItens.id_tipoitem != 1 and vistoriasItens.id_tipoitem != 6  ";
	$query .= " order by vistoriasItens.id_tipoitem, vistoriasItens.id_item ";
	$db->setQuery($query);
	$db->execute();
	$itens_result = $db->getResultSet();
	$old = "";
	foreach ($itens_result as $tipoitem) {
		if($old != $tipoitem['tipo']){
			echo '<td align="center"><b>'. $tipoitem['tipo'] .'</b></td>';
			$old = $tipoitem['tipo'];
		}
	}
?>
	<td><b>Observa&ccedil;&atilde;o</b></td>
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

	<?php
	$old = "";
	$cont = 0;
	echo '<td bgcolor="#FFFFFF"><table><tr>';
	foreach ($itens_result as $tipoitem) {
		$cont++;
		$resecho = "";
		if($cont == 1 ){
			$old = $tipoitem['tipo'];
		}
		if($cont != 1 && $old != $tipoitem['tipo']){
			$resecho .= '</tr></table></td>';
			$resecho .= '<td><table><tr>';
			$old = $tipoitem['tipo'];
		}
		$resecho .= '<td align="center" style="width:25px;border: 1px solid black;">'. $tipoitem['sigla'] .'</td>';
		echo $resecho;
	}
	echo '</tr></table></td>';
	?>
	<!--
	<td>
		<table>
			<tr>
		<td align="center" style="width:40px;border: 1px solid black;">SUJA</td>
		<td align="center" style="width:40px;border: 1px solid black;">SOLTA</td>
			</tr>
		</table>
	</td>
	-->
	<td width="80px" style="border-bottom: 1px solid black;">&nbsp;</td>

</tr>
<?php
	}
?>
</table>
</body>
<script language="javascript">
	window.print();
</script>
</html>