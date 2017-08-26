<?php
#Carga das classes necessárias para o funcionamento do index
include('classes/database.php');
?>
<script type="text/javascript" src="javascript/login.js"></script>
<link rel='stylesheet' type='text/css' href='styles/login.css'/>

<form method="post" action="home.php" name="form_login" id="form_login">

	<input type="hidden" name="id_perfil" id="id_perfil" value="">
	<input type="hidden" name="nome_perfil" id="nome_perfil" value="">
	<input type="hidden" name="id_usuario" id="id_usuario" value="">
	<input type="hidden" name="nome_usuario" id="nome_usuario" value="">
	<input type="hidden" name="permissoes" id="permissoes" value="">

	<fieldset id="login">

		<legend>Controle de acesso</legend>

		<div id="fields">
			<div>
				<span class="label">CPF:</span>
				<span class="field"><input type="text" name="cpf" id="cpf" class="login_text" value=""/></span>
			</div>
			<div>
				<span class="label">Senha:</span>
				<span class="field"><input type="password" name="senha" id="senha" class="login_text" value=""/></span>
			</div>
		</div>

		<div id="image"><img src="images/lock.png" width="32" height="32"/></div>

		<div align="center"><input type="button" name="login_button" id="login_button" value="Entrar"/></div>

		<div align="right"><span id="forgotten">Esqueci minha senha</span></div>

	</fieldset>

</form>