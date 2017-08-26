//----------------------------------------//	

$(document).ready(function() {


//----------------------------------------//			
	$("#insert").click(function() {
		location.href = "home.php?action=perfis_edit&id_perfil=new";
	});
//----------------------------------------//		
	$('#delete').click(function() {
		$("#contents").css("cursor","wait");
		var pesquisa_id = $('input[name=id_perfil]:checked').val();
		if ($.type(pesquisa_id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			$.ajax({
				type:"POST",
				url:"ajax/perfil_delete.php",
				data:{id_perfil: pesquisa_id},
				success: function(response) { if (!isJSON(response)) { return false };
					showMessage("Registro excluído com sucesso.", "redirect", "home.php?action=perfis");
				}
			});
		}
		$("#contents").css("cursor","auto");
	});
//----------------------------------------//
	$('#edit').click(function() {
		var pesquisa_id = $('input[name=id_perfil]:checked').val();	
		if ($.type(pesquisa_id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			location.href='home.php?action=perfis_edit&id_perfil=' + pesquisa_id;
		}
	});


//----------------------------------------//
	
	$("#cancel").click(function() {
		location.href = "home.php?action=perfis"; 
	});

//----------------------------------------//		

	$("#save").click(function() {
		
		$("#contents").css("cursor","wait");

		var height = 0;
		

		//Pegando todos os elementos selecionados do checkbox	
		var arrayPermissoes = [];
 		$('input:checkbox[id=id_permissao]:checked').each(function() {
 			arrayPermissoes.push($(this).val());
		});
		
		
		//Se o nome está vazio, ele é inválido
		if (isEmpty($('#nome').val())) {
			
			showMessage("Nome inválido.", "focus", "filterfield");
			
		} else {
		
			var id_perfil = $('#id_perfil').val();

			//id_equipe -256 é inclusão de perfil
			if (id_perfil == -256) {

				$.ajax({
					type:"POST",
					url:"ajax/perfil_insert.php",
					data:{ nome:$('#nome').val(),
						permissoes:arrayPermissoes
					},
					success: function(response) { if (!isJSON(response)) { return false };
						showMessage("Registro inserido com sucesso!", "redirect", "home.php?action=perfis");
					}
				});
				
			} else {

				$.ajax({
					type: "POST",
					url: "ajax/perfil_update.php",
					data: {
						id_perfil:$('#id_perfil').val(),
						nome:$('#nome').val(),
						permissoes:arrayPermissoes
					},
					success: function(response) { if (!isJSON(response)) { return false };
						showMessage("Registro salvo com sucesso!", "redirect", "home.php?action=perfis");
					}
				});

			}

		}
		
		$("#contents").css("cursor","auto");


	});

//----------------------------------------//

});

//----------------------------------------//