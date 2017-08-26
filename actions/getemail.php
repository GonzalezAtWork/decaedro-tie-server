<?php
#Carga das classes necessárias para o funcionamento do index
include('classes/database.php');
include('classes/dropdown.php');
$drop = new Dropdown();
$select = $drop->getHTMLFromQuery('select id_perfil as code, ent_name as label from enterprises', 1, false, 'enterprises', 'style="font-size:10pt; width:325px;"');
?>
<form method="post" action="index.php" name="form_email" id="form_email">

	<fieldset id="forgotten_password">

		<legend>Recuperação de senha</legend>

		<div align="center" style="padding-top:16px;padding-bottom:16px;">Preencha o CPF ou o E-mail para o lembrete ou redefinição da sua senha.</div>

		<div class="field_line" style="padding-bottom:2px">
			<span class="left_label">Empresa:</span>
			<span><?php echo $select?></span>
		</div>

		<div class="field_line">
			<span class="left_label">CPF:</span>
			<span class="login_field_container"><input type="text" name="cpf" id="cpf" class="medium_field" value=""></span>
		</div>

		<div class="field_line">
			<span class="left_label">E-mail:</span>
			<span class="login_field_container"><input type="text" name="email" id="email" class="medium_field" value=""></span>
		</div>

		<div align="center">
			<input type="button" name="reminder_button" id="reminder_button" value="Lembrete">
			<input type="button" name="unblock_button" id="unblock_button" value="Redefinição">
			<input type="button" name="back_button" id="back_button" value="Voltar">
		</div>
		
	</fieldset>

</form>
<script language="javascript">
//----------------------------------------//			
$(document).ready(function() {

	$("#cpf").focus();
	
//----------------------------------------//			
	$('#send_button').click(function() {

		$("#contents").css("cursor","wait");

		//Não pode preencher CPF e E-Mail ao mesmo tempo
		if ($('#cpf').val() === '' && $('#email').val() === '' ) {

			showMessage('Preencha apenas o CPF ou apenas o e-mail.');
			
		} else if ($('#cpf').val() !== '') {

			//Busca lembrete de senha
			
		} else {

			//Envia novo código de validação de e-mail
			if (isValidEmail($('#email').val())) {
				$.ajax({
					type: "POST",
					url: "ajax/unblock_code_send.php",
					data: {id_perfil:$('#enterprises').val(), email:$('#email').val()},
					success: function(emailSent) {
						if (emailSent) {
							$('#form_email').submit();
						} else {
							showMessage('E-mail não encontrado.');
						}
					}
				});
			} else {
				showMessage('E-mail inv\341lido');
			}
		}
		
   });
//----------------------------------------//
});
</script>