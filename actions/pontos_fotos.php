<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_ponto = (isset($_REQUEST['id_ponto']))?$_REQUEST['id_ponto']:0;
$atual = (isset($_REQUEST['atual']))?$_REQUEST['atual']:0;

$query = ' select count(1) as total from fotografias ';
$query .= ' inner join pontos on fotografias.id_ponto = pontos.id_ponto ';
$query .= ' where pontos.id_ponto = ' . $id_ponto;
$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();
$total = $db_result->total;

	$query = ' select * from pontos ';
	$query .= ' left join fotografias on fotografias.id_ponto = pontos.id_ponto ';
	$query .= ' left join vistoriasItens on fotografias.id_item = vistoriasItens.id_item ';
	$query .= ' where pontos.id_ponto = '. $id_ponto;
	$query .= ' order by data desc ';
	$query .= ' limit 1 offset ' . $atual;

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
		<legend id="titulo">Fotos do Ponto de Parada</legend>
		<div class="line" align="center">
			<span class="field" align="center">
				<b>Simak:</b> <?php echo $db_result->codigo_abrigo;?> 
				<b>Endereço:</b> <?php echo $db_result->endereco;?>
				<b>Total de Fotos:</b> <?php echo $total;?>
			</span>
		</div>
		<?php if($total > 0){?>
			<div align="center">
				<span class="field"><img src="foto.php?id_foto=<?php echo $db_result->id_foto; ?>"/></span>
			</div>
			<div class="line">
				<label style="width:200px">Data:</label>
				<span class="field"><?php echo $db_result->data; ?></span>
			</div>
<?php if( $db_result->id_vistoria != "0" ){ ?>
			<div class="line">
				<label style="width:200px">Vistoria:</label>
				<span class="field"><a href="home.php?action=ocorrencias_edit&id_ocorrencia=<?php echo $db_result->id_ocorrencia;?>"><?php echo $db_result->id_vistoria; ?></a></span>
			</div>
			<div class="line">
				<label style="width:200px">Item Vistoriado:</label>
				<span class="field"><?php echo $db_result->nome; ?></span>
			</div>
<?php } ?>
<?php if( $db_result->id_os != "0" ){ ?>
			<div class="line">
				<label style="width:200px">Ocorrência:</label>
				<span class="field"><a href="home.php?action=ocorrencias_edit&id_ocorrencia=<?php echo $db_result->id_ocorrencia;?>"><?php echo $db_result->id_ocorrencia; ?></a></span>
			</div>
			<div class="line">
				<label style="width:200px">Manutenção Realizada:</label>
				<span class="field"><?php echo $db_result->nome; ?></span>
			</div>
<?php } ?>
		<?php }else{?>
			<div class="line" align="center">
				<span class="field">Nenhuma fotografia encontrada.</span>
			</div>
		<?php }?>
		<div align="center" style="padding-top:10px;">
		<?php if($atual > 0){?>
			<input type="button" value="     Anterior     " onclick="location.href='home.php?action=pontos_fotos&atual=<?php echo $atual-1;?>&id_ponto=<?php echo $id_ponto;?>'">
		<?php }?>
			<input type="button" value="Dados do Ponto" onclick="location.href='home.php?action=pontos_edit&id_ponto=<?php echo $id_ponto;?>'"/>
		<?php if($atual+1 < $total){?>
			<input type="button" value="     Próxima     " onclick="location.href='home.php?action=pontos_fotos&atual=<?php echo $atual+1;?>&id_ponto=<?php echo $id_ponto;?>'"/>
		<?php }?>
		</div>
	</fieldset>
</form>