<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_tipo = $_REQUEST['id_tipo'];
$query = 'select * from pontosTipo where id_tipo = ' . $id_tipo;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();

?>

<script type="text/javascript" src="javascript/pontosTipo.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jquery.colorPicker.js"/></script>

<script type="text/javascript">
$(function() {    
	$('#cor').colorPicker();
});
</script>

<link rel="stylesheet" href="styles/colorPicker.css" type="text/css" />
<form name="form" id="form" method="post">

	<input type="hidden" name="id_tipo" id="id_tipo" value="<?php echo $db_result->id_tipo; ?>">

	<fieldset id="groups">
	
		<legend>Dados do Registro</legend>
		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $db_result->nome?>"></span>
		</div>
		<div class="line">
		<table><tr><td>
			<label>Cor:</label>
			</td><td>
			<span class="field"><input style="width:20px" id="cor" type="text" name="cor" value="#<?php echo $db_result->cor?>" /></span>
			</td></tr></table>
		</div>
		<div class="line">
			<label>Totem:</label>
			<span class="field"><input type="checkbox" name="totem" id="totem" <?php echo ($db_result->totem == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
		</div>

	</fieldset>

</form>