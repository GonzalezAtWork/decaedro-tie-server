//----------------------------------------//

$(document).ready(function() {

//----------------------------------------//

	$("#save").click(function() {

		var message = '';

		if (isEmpty($('#novasenha').val()) || isEmpty($('#confirm').val())) {
			message = 'Todos os campos s\343o obrigat\363rios!';
		} else if ($('#novasenha').val() != $('#confirm').val()) {
			message = 'Senha e confirma\347\343o s\343o diferentes!';
		}

		if (isEmpty(message)) {
			$.ajax({
				type: "POST",
				url: "ajax/changepass.php",
				data: {
					userId:$('#id_usuario').val(),
					novasenha:$('#novasenha').val(),
				},
				success: function(updated) {
					//alert(updated);
					showMessage('Senha atualizada com sucesso!', 'redirect', 'index.php?action=logout');
				}
			});
		} else {
			showMessage(message, 'focus', 'novasenha');
		}

	});

//----------------------------------------//			

});

//----------------------------------------//