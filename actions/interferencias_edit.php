<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_interferencia = $_REQUEST['id_interferencia'];
$query = 'select * from interferencias where id_interferencia = ' . $id_interferencia;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();

?>

<script type="text/javascript" src="javascript/interferencias.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_interferencia" id="id_interferencia" value="<?php echo $db_result->id_interferencia; ?>">

	<fieldset id="groups" style="width:540px;">
	
		<legend>Dados da Interferência</legend>
		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $db_result->nome?>"></span>
		</div>
		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
		</div>

	</fieldset>

</form>