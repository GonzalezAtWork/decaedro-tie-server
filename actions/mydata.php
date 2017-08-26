<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include("classes/user.php");

$user = new User;
$user->load();

$drop = new Dropdown();
$select = $drop->getHTMLFromQuery('select id_perfil as code, nome as label from perfis order by 2', $user->id_perfil , false, 'id_perfil');
?>

<script type="text/javascript" src="javascript/mydata.js"></script>

<form name="mydata" id="mydata" method="post">

	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $user->id_usuario; ?>">

	<fieldset id="small">

		<legend>Meus dados</legend>

		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $user->nome;?>"></span>
		</div>

		<div class="line">
			<label>Perfil:</label>
			<span><?php echo $select;?></span>
		</div>

		<div class="line">
			<label>CPF:</label>
			<span class="field"><input type="text" name="cpf" id="cpf" class="short_field" value="<?php echo $user->cpf;?>"></span>
		</div>

		<div class="line">
			<label>E-mail:</label>
			<span class="field"><input type="text" name="email" id="email" class="medium_field" value="<?php echo $user->email;?>"></span>
		</div>

		<div class="line">
			<label>Celular:</label>
			<span class="field">
				<input type="text" name="ddd" id="ddd" class="tiny_field" value="<?php echo $user->ddd;?>">
				<input type="text" name="celular" id="celular" class="short_field" value="<?php echo $user->celular;?>" maxlength="10">
			</span>
		</div>

		<div id="buttons">
			<span><input type="button" name="save" id="save" value="Salvar"></span>
		</div>

	</fieldset>

</form>