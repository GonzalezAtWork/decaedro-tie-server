//----------------------------------------//	

$(document).ready(function() {

//----------------------------------------//

	$('#edit').click(function() {

		$("#contents").css("cursor","wait");

		var id_equipe = $('input[name=id_equipe]:checked').val();
		
		if ($.type(id_equipe) === "undefined") {
			showMessage("Nenhuma equipe selecionada.", "focus", "filterfield");
		} else {
			location.href='home.php?action=equipes_edit&id_equipe='+id_equipe;
		}

		$("#contents").css("cursor","auto");

	});

//----------------------------------------//

    $('#delete').click(function() {

        $("#contents").css("cursor","wait");

        var id_equipe = $('input[name=id_equipe]:checked').val();

        if ($.type(id_equipe) === "undefined") {
            showMessage("Nenhuma equipe selecionada.", "focus", "filterfield");
        } else {

            $.ajax({
                type:"POST",
                url:"ajax/equipes_delete.php",
                data:{id_equipe:id_equipe},
                success: function(response) {
                    if (!isJSON(response)) {
                        return false;
                    };
                    showMessage("Equipe excluída com sucesso.", "redirect", "home.php?action=equipes");
                }
            });

        }

        $("#contents").css("cursor","auto");

    });

//----------------------------------------//

	$("#generate").click(function() {

		$("#contents").css("cursor","wait");

		$.ajax({
			type: "POST",
			url: "ajax/equipes_generate.php",
			success: function(result) {

				if (!isJSON(result)) {
					return false;
				} else {
					var objEquipes = eval( '(' + result + ')' );
					if (objEquipes != null) {
						showMessage('Equipe gerada com sucesso para '+objEquipes[data]+'.', 'focus', 'senha');
					} else {
						showMessage('Erro na geração das equipes de '+objEquipes[data]+'.', 'focus', 'senha');
					}
				}

			}
		});

		$("#contents").css("cursor","auto");

		location.href = "home.php?action=equipes";

	});


//----------------------------------------//

	$("#save").click(function() {


		if (isEmpty($('#nome').val())) {
			
			showMessage("Nome inválido!", "focus", "filterfield");
			
		} else {

			var id_equipe = $('#id_equipe').val();

			// id_equipe -256 é inclusão de equipe
			if (id_equipe == -256) {

				$.ajax({
					type:"POST",
					url:"ajax/equipes_insert.php",
					data:{
						nome:$('#nome').val(),
						lavagem:valLavagem,
						usuarios:usuarios
					},
					success: function(response) { if (!isJSON(response)) { return false };
						showMessage("Registro inserido com sucesso!", "redirect", "home.php?action=equipes");
					}
				});
				
			} else {

				$.ajax({
					type: "POST",
					url: "ajax/equipes_update.php",
					data: {
						id_equipe:$('#id_equipe').val(),
						nome:$('#nome').val(),
						lavagem:valLavagem,
						usuarios:usuarios
					},
					success: function(response) { if (!isJSON(response)) { return false };
						showMessage("Registro salvo com sucesso!", "redirect", "home.php?action=equipes");
					}
				});

			}

		}

	});
	
//----------------------------------------//

	$('#filterbutton').click(function() {

		if ($('#filtertext').val() === "") {
			showMessage("Filtro inválido.<br>A carga de dados será feita sem nenhum filtro.", "redirect", "home.php?action=equipes&filter=none", "long size");
		} else {
			var field = $("#filterfield option:selected").val();
			var text = $("#filtertext").val();
			location.href = "home.php?action=equipes&filterfield="+field+"&filtertext="+text; 
		}

	});

//----------------------------------------//

});

//----------------------------------------//