<script type="text/javascript" src="javascript/sendpassword.js"></script>
<link rel='stylesheet' type='text/css' href='styles/login.css' />  

<form name="sendPassword" id="sendPassword" method="post">

	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="login">
	
		<legend>Envio de senha</legend>
		
		<p>Digite o seu CPF e clique em "Enviar" para que a sua senha seja encaminhada ao seu e-mail cadastrado.</p>
		
		<div align="center">
			<span id="label" style="margin-left:-35px;">CPF:</span>
			<span id="field"><input type="text" name="cpf" id="cpf" class="login_text"/></span>
		</div>
				
		<div align="center">
			<input type="button" name="send_button" id="send_button" value="Enviar">
		</div>

		<div id="getback">Voltar ao login</div>
		
	</fieldset>

</form>