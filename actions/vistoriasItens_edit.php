<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_item = $_REQUEST['id_item'];
$query = 'select * from vistoriasItens where id_item = ' . $id_item;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();

?>

<script type="text/javascript" src="javascript/vistoriasItens.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_item" id="id_item" value="<?php echo $db_result->id_item; ?>">

	<fieldset id="groups" style="width:540px;">
	
		<legend>Dados do Item de Vistoria</legend>
		<div class="line">
			<label>Código:</label>
			<span class="field"><input type="text" name="codigo" id="codigo" class="medium_field" value="<?php echo $db_result->codigo?>"></span>
		</div>
		<div class="line">
			<label>Sigla:</label>
			<span class="field"><input type="text" name="sigla" id="sigla" class="small_field" value="<?php echo $db_result->sigla?>"></span>
		</div>
		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $db_result->nome?>"></span>
		</div>
		<div class="line">
			<label>Fotografia Obrigatória:</label>
			<span class="field"><input type="checkbox" name="foto" id="foto" <?php echo ($db_result->foto == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label>Crítico:</label>
			<span class="field"><input type="checkbox" name="critico" id="critico" <?php echo ($db_result->critico == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label>Chuva:</label>
			<span class="field"><input type="checkbox" name="chuva" id="chuva" <?php echo ($db_result->chuva == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label>Urgente:</label>
			<span class="field"><input type="checkbox" name="urgente" id="urgente" <?php echo ($db_result->urgente == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label>Item de Cotia:</label>
			<span class="field"><input type="checkbox" name="cotia" id="cotia" <?php echo ($db_result->cotia == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label>Item de Elétrica:</label>
			<span class="field"><input type="checkbox" name="eletrica" id="eletrica" <?php echo ($db_result->eletrica == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label>Cobertura Maior:</label>
			<span class="field"><input type="checkbox" name="cobertura_maior" id="cobertura_maior" <?php echo ($db_result->cobertura_maior == 't')?' checked ':'';?> value="TRUE"></span>
		</div>

		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
		</div>

	</fieldset>

</form>