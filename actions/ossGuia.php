<?php

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', true);

$id_os = $_REQUEST['id_os'];

include('../classes/XMLObject.php');
include('../classes/database.php');

$db = Database::getInstance();

	$query  = " select oss.*, eq.equipe, rt.roteiro, vis.qtd_pontos from oss ";
/*
	$query .= " left join ( ";
	$query .= "		select id_os, string_agg(nome,', ') as equipe from ossEquipes ";
	$query .= "			inner join equipes on equipes.id_equipe = ossEquipes.id_equipe ";
	$query .= "		group by id_os ";
	$query .= " ) as eq on oss.id_os = eq.id_os";
*/

	$query .= " left join ( ";
	$query .= "		select id_os, string_agg(nome,', ') as equipe from ossEquipes ";
	$query .= "			inner join usuarios on usuarios.id_usuario = ossEquipes.id_equipe ";
	$query .= "		group by id_os ";
	$query .= " ) as eq on oss.id_os = eq.id_os";

	$query .= " left join ( ";
	$query .= "		select id_os, string_agg(nome,', ') as roteiro from ossRoteiros ";
	$query .= "			inner join roteiros on roteiros.id_roteiro = ossRoteiros.id_roteiro ";
	$query .= "		group by id_os ";
	$query .= " ) as rt on oss.id_os = rt.id_os";

	$query .= " left join ( ";
	$query .= "		select id_os, sum(qtd_pontos) as qtd_pontos from ossRoteiros group by id_os ";
	$query .= " ) as vis on oss.id_os = vis.id_os";

	$query .= " where oss.id_os = " . $id_os;
	$query .= " order by oss.data ";

	////echo $query;

	$db->setQuery($query);
	$db->execute();

$retorno= $db->getResultSet();
if(is_array($retorno)){
	$retorno = $retorno[0];
}
$dia_semana = array("Domingo", "Segunda", "Terça" , "Quarta" , "Quinta", "Sexta", "Sábado");
$data_atual = date("d/m/Y");


	$query = " select * from vistoriasItens ";
	$db->setQuery($query);
	$db->execute();
	$vistoriasItens= $db->getResultSet();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" data-cast-api-enabled="true">		
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Ordem de Serviço</title>
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
<?php if( $retorno['id_prioridade'] == '3'){ ?>
	<p align="center"><span class="c4">Ordem de Serviço de <u>TROCA DE CARTAZES</u></span></p>
<?php }else{ ?>
	<p align="center"><span class="c4">Ordem de Serviço de Manutenção</span></p>
	<?php if( $retorno['id_prioridade'] == '1'){ ?>
		<p align="center"><span class="c4"><small>Prioridade <u>URGENTE</u></small></span></p>
	<?php } ?>
	<?php if( $retorno['id_prioridade'] == '2'){ ?>
		<p align="center"><span class="c4"><small>Prioridade <u>NORMAL</u></small></span></p>
	<?php } ?>
<?php } ?>
<?php
	$query = "";
	$query .= " select pontos.*, ocorrencias.*, usuarios.nome as equipe, roteiros.nome as roteiro from ocorrencias ";
	$query .= " inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
	$query .= " left join usuarios on ocorrencias.id_equipe = usuarios.id_usuario ";
	$query .= " left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
	$query .= " where id_os = " . $id_os;
	$query .= " and ocorrencias.executada = false ";
	$query .= " order by ocorrencias.id_equipe, roteiros.id_roteiro,  posicao ";

	//echo $query;

	$db->setQuery($query);
	$db->execute();

	$result = $db->getResultSet();

	$total_pontos = $db->getRows();
?>
<table width="100%" border="0">
<tr>
<td width="200">&nbsp;</td>
<td align="center">Total: <b><?php echo$total_pontos;?></b> Ocorrências &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; Emitida em <b><?php echo $data_atual;?></b></td>
<td width="200"><p align="right"><b>Código:</b> <?php echo substr('000000' . $id_os, -5);?></p></td>
</tr>
<tr><td colspan="10" align="center">PLACA: __________________    MAQ: __________________    GPS: __________________</td></tr>
</table>
<table width="100%">
<?php
	$id_equipe = "";
	$id_roteiro = "";
	$linhas = 4;
	foreach ($result as $row) {
	if($id_equipe != $row['id_equipe']){ 
		$id_equipe = $row['id_equipe'];
		$id_roteiro = "";

		?>
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr><td colspan="10">Listagem de serviços p/ equipe: <b><?php echo strtoupper($row['equipe']);?></b></td></tr>
		<tr><td colspan="10" style="border-bottom: 3px solid black;"></td></tr>
		<?
		$linhas++;
	}

	if($id_roteiro != $row['id_roteiro']){ 
		$id_roteiro = $row['id_roteiro'];
		?>
		<tr><td colspan="10" align="right"><U>Roteiro: <B><?php echo strtoupper($row['roteiro']);?></B></U></td></tr>
		<?
		$linhas++;

	}
	?>
	<tr>
		<td colspan="10" style="border-bottom: 1px solid black;">Simak: <b><?php echo $row['codigo_abrigo'];?></b> - Endereço: <b><?php echo $row['endereco'];?></b></td>
	</tr>
	<?php
	$linhas++;
	$nomeimagenspublicidade = $row['nomeimagenspublicidade'];
	if($nomeimagenspublicidade != ""){
		$nomeimagenspublicidade = explode("|", $nomeimagenspublicidade);
		for ($a = 0; $a < sizeof($nomeimagenspublicidade); $a++ ){
			$nova = explode(";",$nomeimagenspublicidade[$a]);
			$id_item = explode(",",$nova[0]);
			$antiga = $id_item[1];
			$id_item = $id_item[0];
			$nova = $nova[1];
			$nomeimagenspublicidade[$a] = array($id_item, $antiga, $nova);
		}
	}
	$itens = explode(',',$row['itensvistoria']);
	foreach ($vistoriasItens as $item) {
		if (array_search($item['id_item'], $itens) !== false) {

			$linhas++;
			echo '			<tr>';
			echo '			<td width="10"><input type="checkbox"/></td>';	
			echo '			<td width="220">'. $item['nome'] .'</td>';		

			for ($a = 0; $a < sizeof($nomeimagenspublicidade); $a++ ){
				if($item['id_item'] == $nomeimagenspublicidade[$a][0]){
					if($nomeimagenspublicidade[$a][1] == $nomeimagenspublicidade[$a][2]){
						echo '			<td width="200">- CHECK-IN FOTOGRÁFICO:</td>';
						echo '			<td colspan="2"><b><u>' . strtoupper($nomeimagenspublicidade[$a][1]) . '</u></b></td>';
						echo '			<td width="130">FOTO: ___________</td>';
					}else{
						echo '			<td width="200">- TROCA DE CARTAZ:</td>';
						echo '			<td width="200"><b>ANTES:</B> <u>' . strtoupper($nomeimagenspublicidade[$a][1]) . '</u></b></td>';
						echo '			<td width="200">- <b>ATUAL:</B> <u>' . strtoupper($nomeimagenspublicidade[$a][2]) . '</u></b></td>';
						echo '			<td width="130">FOTO: ___________</td>';
					}
				}
			}
			echo '			</tr>';
		}
	}
?>
<?php if( $row['observacaovistoria'] != ""){ ?>
	<tr><td width="10">&nbsp;</td><td colspan="10"><Input type="checkbox"> OBS: <?php echo $row['observacaovistoria'];?></td></tr>
<?php 
	$linhas++;
	} ?>
	<tr><td colspan="10">&nbsp;</td></tr>
	<?php

		if($linhas > 30){
			echo '</table><table width="100%" style="page-break-before: always;"><tr><td colspan="10">&nbsp;</td></tr>';
			$linhas = 0;
		}
	}
?>
</table>
<?php

$query  = " select ";
$query .= "		publicidadeimagens.nome  ";
$query .= "		from publicidadeimagens  ";
$query .= "		 where publicidadeimagens.nome in ( ";
$query .= "			select distinct ";
$query .= "			unnest( ";
$query .= "				string_to_array( ";
$query .= "					trim( both ';' from  ";
$query .= "						replace(  ";
$query .= "							replace(  ";
$query .= "								replace(  ";
$query .= "									replace(  ";
$query .= "										replace(  ";
$query .= "											string_agg( '|' || nomeimagenspublicidade, ';')  ";
$query .= "										, '|53,', ';' ) ";
$query .= "									, '|52,', ';' ) ";
$query .= "								, '|51,', ';' ) ";
$query .= "							, '|50,', ';' )  ";
$query .= "						, ';;', ';' )  ";
$query .= "					) ";
$query .= "				,';') ";
$query .= "			) as nome ";
$query .= "			from ocorrencias where ocorrencias.id_os = " . $id_os . " and nomeimagenspublicidade is not null ";
$query .= "		 ) ";

	$db->setQuery($query);
	$db->execute();
	if ( $db->getRows() > 0 ) {
?>
	<table width="100%" border="0" style="page-break-before: always;">
	<tr>
		<td align="center" colspan="10"><b>Imagens dos Cartazes que serão trocados</b></td>
	</tr>
	<tr><td align="center" colspan="10">&nbsp;</td></tr>
	<?php

	$publicidadeimagens = $db->getResultSet();	
	$contador = 0;
	foreach ($publicidadeimagens as $imagem) {
		$contador++;
		if ($contador == 1){
			echo '<tr>';
		}
		echo '<td align="center"><img src="../imagem.php?nome='. base64_encode( $imagem['nome'] ).'" width="150"/></td>';
		if ($contador == 4){
			echo '</tr>';
			echo '<tr><td align="center" colspan="10">&nbsp;</td></tr>';
			$contador = 0;
		}
	}
	?>
	</table>
<?php 
	}	
?>
</body>
<script language="javascript">
	window.print();
</script>
</html>