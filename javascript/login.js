//----------------------------------------//

$(document).ready(function() {

//----------------------------------------//

	//Captura da tecla enter para enviar o login
	$(document).keydown(function(e) {
	    e.stopPropagation();
	    if (e.keyCode === 13) {
	    	$('#login_button').click();
	    }
	});
	
//----------------------------------------//
	
	$("#cpf").focus();

//----------------------------------------//
	
	jQuery(function($) {
		$("#cpf").mask("999.999.999-99");
	});

//----------------------------------------//
	$('#forgotten').click(function(){
		$(location).attr('href','index.php?lostpass=true');
	});
	
//----------------------------------------//
	$('#login_button').click(function() {

		$("#contents").css("cursor","wait");

		cpf_usuario = cleanUpCPF($("#cpf").val());
		senha_usuario = md5($("#senha").val());
		
		if (isCPFValid(cpf_usuario)) {

			$.ajax({
				type: "POST",
				url: "ajax/login_search.php",
				data: {cpf:cpf_usuario, senha:senha_usuario},
				success: function(user) {

					if (!isJSON(user)) {

						return false

					} else {

						var objUser = eval( '(' + user + ')' );

						if (objUser != null) {

							$("#id_usuario").val(objUser.id_usuario);
							$("#nome_usuario").val(objUser.nome_usuario);
							$("#id_perfil").val(objUser.id_perfil);
							$("#nome_perfil").val(objUser.nome_perfil);
							$("#permissoes").val(objUser.permissoes);
							$("#form_login").submit();

						} else {

							showMessage('Senha incorreta', 'focus', 'senha');

						}
					}

				}
			});

		} else {

			showMessage('CPF inválido', 'focus', 'cpf');

		}

   });
//----------------------------------------//
 
});

//----------------------------------------//
