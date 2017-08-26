<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$db = Database::getInstance();

if (empty($_REQUEST['id_status'])) {
   $id_status = -256;
   $nome = "";
   $observacoes = "";
} else {
   $query = "select nome, observacoes from pontosStatus where id_status = ".$_REQUEST['id_status'];
   $db->setQuery($query);
   $db->execute();
   $result = $db->getResultAsObject();
   $id_status = $_REQUEST['id_status'];
   $nome = $result->nome;
   $observacoes = $result->observacoes;
}
?>

<script type="text/javascript" src="javascript/pontosStatus.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_status" id="id_status" value="<?php echo $id_status; ?>">

	<fieldset id="small">
	
		<legend>Dados do Registro</legend>
		
		<div style="textarea_wrapper">
		
			<div>
				<label>Nome:</label>
				<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $nome; ?>"></span>
			</div>
			
			<div>
				<label class="top">Observações:</label>
				<span class="field"><textarea name="observacoes" id="observacoes" class="medium_textarea"><?php echo $observacoes; ?></textarea></span>
			</div>

		</div>
		
		<div id="buttons">
			<input type="button" name="save" id="save" value="Salvar">
			<input type="button" name="cancel" id="cancel" value="Cancelar">
		</div>

	</fieldset>

</form>