<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include("classes/user.php");

$user = new User;

if (empty($_REQUEST['id_usuario'])) {
	$id_usuario = -256;
   $buttonCaption = "Incluir";
   $firstBlank = true;
} else {
	$id_usuario = $_REQUEST['id_usuario'];
	$user->load($id_usuario);
	$buttonCaption = "Salvar";
   $firstBlank = false;
}

$drop = new Dropdown();
$select = $drop->getHTMLFromQuery('select id_perfil as code, nome as label from perfis order by 2', $user->id_perfil, $firstBlank, 'id_perfil');
$servidor = $drop->getHTMLFromQuery('select id_servidor as code, url as label from servidores', $user->id_servidor, $firstBlank, 'id_servidor');
?>

<script type="text/javascript" src="javascript/users.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario;?>">

	<fieldset id="small">

		<legend>Dados do Usuário</legend>

		<div class="line">
			<label>Servidor:</label>
			<span><?php echo $servidor?></span>
		</div>

		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $user->nome?>"></span>
		</div>

		<div class="line">
			<label>Nome Completo:</label>
			<span class="field"><input type="text" name="nome_completo" id="nome_completo" class="medium_field" value="<?php echo $user->nome_completo?>"></span>
		</div>

		<div class="line">
			<label>Perfil:</label>
			<span><?php echo $select?></span>
		</div>

		<div class="line">
			<label>CPF:</label>
			<span class="field"><input type="text" name="cpf" id="cpf" class="short_field" value="<?php echo $user->cpf?>"></span>
		</div>

		<div class="line">
			<label>E-mail:</label>
			<span class="field"><input type="text" name="email" id="email" class="medium_field" value="<?php echo $user->email?>"></span>
		</div>

		<div class="line">
			<label>Celular:</label>
			<span class="field">
				<input type="text" name="ddd" id="ddd" class="tiny_field" value="<?php echo $user->ddd?>" maxlength="2">
				<input type="text" name="celular" id="celular" class="short_field" value="<?php echo $user->celular?>" maxlength="10">
			</span>
		</div>		

		<div id="buttons">
			<span><input type="button" name="save" id="save" value="<?php echo $buttonCaption?>"></span>
			<!-- <span><input type="button" name="keygen" id="keygen" value="Nova Senha"></span> -->
			<span><input type="button" name="cancel" id="cancel" value="Cancelar"></span>
		</div>

	</fieldset>

</form>