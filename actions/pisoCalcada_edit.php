<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_piso_calcada = $_REQUEST['id_piso_calcada'];
$query = 'select * from pisoCalcada where id_piso_calcada = ' . $id_piso_calcada;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();

?>

<script type="text/javascript" src="javascript/pisoCalcada.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_piso_calcada" id="id_piso_calcada" value="<?php echo $db_result->id_piso_calcada; ?>">

	<fieldset id="groups" style="width:540px;">
	
		<legend>Dados do Piso da Calçada</legend>
		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $db_result->nome?>"></span>
		</div>
		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
		</div>

	</fieldset>

</form>