//----------------------------------------//			
$(document).ready(function() {

	$("#cpf").focus();

//----------------------------------------//			
	jQuery(function($) {
		$("#cpf").mask("999.999.999-99");
	});

//----------------------------------------//			
	$('#getback').click(function(){
		$(location).attr('href','index.php?action=logout');
	});

//----------------------------------------//
	$('#send_button').click(function() {

		$("#contents").css("cursor","wait");

		cpf_usuario = cleanUpCPF($("#cpf").val());
				
		if (isCPFValid(cpf_usuario)) {

			$.ajax({
				type: "POST",
				url: "ajax/send_password.php",
				data: {cpf:cpf_usuario},
				success: function(response) { if (!isJSON(response)) { return false };

					if (response == "TRUE") {
						showMessage('Sua conta foi modificada com sucesso.\n\nA nova senha foi enviada para o seu endereço de e-mail.', 'redirect', 'index.php?action=logout', 'double');
					} else {
						showMessage('O envio da nova senha falhou!<br>Por favor, entre contato com o suporte.', 'redirect', 'index.php?action=logout', 'double');
					}
				}
			});

		} else {

			showMessage('CPF inv\341lido', 'focus', 'cpf');

		}

   });
//----------------------------------------//
 
});

//----------------------------------------//
