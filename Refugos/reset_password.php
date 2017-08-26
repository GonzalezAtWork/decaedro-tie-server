<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

#Inclui cabeçalho para index
include('includes/head.php');

echo '<div id="blueline"></div>';

if (!$_REQUEST['formSent']) {
	?>
	<form method="post">
	
		<input type="hidden" name="formSent" id="formSent" value="true">
	
		<fieldset id="reset">
			<legend>Redefinição de senha</legend>
				<div>Digite o seu endereço de e-mail para receber as instruções de redefinição de senha.</div>
				<div class="field_line">
					<span class="left_label">Endereço de e-mail:</span>
					<span class="login_field_container"><input type="text" name="email" id="email" value=""></span>
				</div>
		</fieldset>
	
	</form>
	<?php 	
} else {
	#Carga das classes necessárias para o funcionamento do index
	include('classes/database.php');
	
}


#Inclui o rodapé para index
include("includes/tail.php");
?>