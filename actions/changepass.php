<script type="text/javascript" src="javascript/changepass.js"></script>

<form name="changepass" id="changepass" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset style="width:520px;">
	
		<legend>Alterar senha</legend>

		<div class="line">
			<label>Senha:</label>
			<span class="field"><input type="password" name="novasenha" id="novasenha" class="short_field" value=""></span>
		</div>

		<div class="line">
			<label>Confirmar Senha:</label>
			<span class="field"><input type="password" name="confirm" id="confirm" class="short_field" value=""></span>
		</div>

		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Alterar">
		</div>

	</fieldset>

</form>