<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_inclinacao = $_REQUEST['id_inclinacao'];
$query = 'select * from inclinacoes where id_inclinacao = ' . $id_inclinacao;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();

?>

<script type="text/javascript" src="javascript/inclinacoes.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_inclinacao" id="id_inclinacao" value="<?php echo $db_result->id_inclinacao; ?>">

	<fieldset id="groups">
	
		<legend>Dados do Registro</legend>
		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $db_result->nome?>"></span>
		</div>
		
		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
		</div>

	</fieldset>

</form>