//----------------------------------------//

var thisAction = 'users';

//----------------------------------------//

$(document).ready(function() {

//----------------------------------------//

	jQuery(function($) {
		$("#cpf").mask("999.999.999-99");
	});

//----------------------------------------//

	$("#insert").click(function() {
		location.href = 'home.php?action=' + thisAction + '_edit';
	});

//----------------------------------------//

	$('#delete').click(function() {

		$("#contents").css("cursor","wait");

		var id = $('input[name=id_usuario]:checked').val();

		if ($.type(id) === "undefined") {

			showMessage("Nenhum registro selecionado.");

		} else {

			$.ajax({
				type:"POST",
				url:"ajax/user_delete.php",
				data:{id_usuario: id},
				success: function(response) {
					if (!isJSON(response)) {
						return false
					};
					showMessage("Registro excluído com sucesso.", "redirect", "home.php?action=users");
				}
			});

		}

		$("#contents").css("cursor","auto");

	});

//----------------------------------------//

	$('#edit').click(function() {

		var id = $('input[name=id_usuario]:checked').val();

		if ($.type(id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			location.href='home.php?action=users_edit&id_usuario=' + id;
		}

	});

//----------------------------------------//		

	$("#cancel").click(function() {
		location.href = 'home.php?action=' + thisAction;
	});

//----------------------------------------//

	$("#export").click(function() {
		location.href = 'includes/excel_export.php';
	});

//----------------------------------------//
	
	$("#save").click(function() {

		var id = $('#id_usuario').val();
		
		//id_usuario -256 é inclusão de id_usuario
		if (id == -256) {

			$.ajax({
				type:"POST",
				url:"ajax/user_insert.php",
				data:{
					id_servidor:$("#id_servidor option:selected").val(),
					perfil:$("#id_perfil option:selected").val(),
					cpf:cleanUpCPF($('#cpf').val()),
					nome:$('#nome').val(),
					nome_completo:$('#nome_completo').val(),
					senha:$('#senha').val(),
					email:$('#email').val(),
					ddd:$('#ddd').val(),
					celular:cleanup($('#celular').val(), '-')
				},
				success: function(response) {
					if (!isJSON(response)) {
						if ((response.indexOf("duplicate key") >= 0) || (response.indexOf("duplicar valor") >= 0)) {
							showMessage("Erro: nome duplicado.", "focus", "nome");
						}
						return false;
					} else {
						showMessage('Registro inserido com sucesso.', 'redirect', 'home.php?action=' + thisAction);
					}
				}
			});
			
		} else {
	
			$.ajax({
				type: "POST",
				url: "ajax/user_update.php",
				data: {
					id_servidor:$("#id_servidor option:selected").val(),
					usuario:id,
					perfil:$("#id_perfil option:selected").val(),
					cpf:cleanUpCPF($('#cpf').val()),
					nome:$('#nome').val(),
					nome_completo:$('#nome_completo').val(),
					email:$('#email').val(),
					ddd:$('#ddd').val(),
					celular:cleanup($('#celular').val(), '-')
				},
				success: function(response) {
					if (!isJSON(response)) {
						if ((response.indexOf("duplicate key") >= 0) || (response.indexOf("duplicar valor") >= 0)) {
							showMessage("Erro: nome duplicado.", "focus", "nome");
						}
						return false;
					} else {
						showMessage('Registro salvo com sucesso.', 'redirect', 'home.php?action=' + thisAction);
					}
				}
			});
	
		}

	});
	
//----------------------------------------//

});

//----------------------------------------//