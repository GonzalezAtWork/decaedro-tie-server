//----------------------------------------//

$(document).ready(function() {

	$("#simak").focus();

//----------------------------------------//

	jQuery(function($) {
		$.mask.definitions['f'] = "[EeIi]"
		$("#semana").mask("99");
		$("#face").mask("f");
	});

//----------------------------------------//

	//Evita que o ENTER submeta o formulário
	$('form').on('submit', function(event){
		event.preventDefault();
	});

//----------------------------------------//

	//Captura da tecla enter para enviar o login
	$(document).keydown(function(e) {
		e.stopPropagation();
		if (e.keyCode === 13) {
			$('#save').click();
		}
	});

//----------------------------------------//

	$("#cancel").click(function() {
		location.href = "home.php?action=publicidadeVeiculacao";
	});

//----------------------------------------//

	$("#save").click(function() {

		var id_veiculacao = $("#id_veiculacao").val();

		//id_veiculacao == undefined é inclusão de veiculacao
		if (isEmpty(id_veiculacao)) {

			if ($('#simak').val() == '' || isNaN($('#simak').val())) {

				showMessage('Simak inválido.', "focus", "simak");

			} else if ($('#semana').val() == '' ) {

				showMessage('Semana inválida.', "focus", "semana");

			} else if ($('#face').val() == '') {

				showMessage('Face inválida.', "focus", "face");

			} else if ($('#nome_imagem').val() == '') {

				showMessage('Nome da imagem inválido.', "focus", "nome_imagem");

			} else {

				$.ajax({
					type:"POST",
					url:"ajax/publicidadeVeiculacao_insert.php",
					data:{
						simak:$('#simak').val(),
						semana:$('#semana').val(),
						face:$('#face').val(),
						nome_imagem:$('#nome_imagem').val(),
					},
					success: function(response) {
						if (!isJSON(response)) {
							return false;
						} else {
							showMessage("Registro inserido com sucesso!", "redirect", "home.php?action=publicidadeVeiculacao");
						}
					}
				});

			}

		} else {

			$.ajax({
				type: "POST",
				url: "ajax/publicidadeImagens_update.php",
				data: {
					id_veiculacao:$('#id_veiculacao').val(),
					simak:$('#simak').val(),
					semana:$('#semana').val(),
					face:$('#face').val(),
					nome_imagem:$('#nome_imagem').val(),
				},
				success: function(response) {
					if (!isJSON(response)) {
						return false;
					} else {
						showMessage("Registro atualizado com sucesso!", "redirect", "home.php?action=publicidadeImagens");
					}
				}
			});

		}

	});

//----------------------------------------//

});

