//----------------------------------------//

$(document).ready(function() {

	var currentURL = $(location).attr('href');

	$("#filtertext").focus();

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
			$('#filterbutton').click();
		}
	});

//----------------------------------------//

	$("#insert").click(function() {
		location.href = "home.php?action=publicidadeImagens_edit";
	});

//----------------------------------------//

	$('#delete').click(function() {
		$("#contents").css("cursor","wait");
		var radio_id_imagem = $('input[name=radio_id_imagem]:checked').val();
		if ($.type(radio_id_imagem) === "undefined") {
			showMessage("Nenhuma imagem selecionada.", "focus", "filtertext");
		} else {
			$.ajax({
				type: "POST",
				url: "ajax/publicidadeImagens_delete.php",
				data: {id_imagem:radio_id_imagem},
				success: function(response) {
					showMessage("Imagem excluída com sucesso.", "redirect", "home.php?action=publicidadeImagens");
				}
			});
		}
		$("#contents").css("cursor","auto");
	});
	   
//----------------------------------------//

	$('#duplicate').click(function() {

		var radio_id_imagem = $('input[name=radio_id_imagem]:checked').val();
		if ($.type(radio_id_imagem) === "undefined") {
			showMessage("Nenhuma imagem selecionada.", "focus", "filterfield");
		} else {
			location.href='home.php?action=publicidadeImagens_edit&id_imagem=' + radio_id_imagem;
		}

	});
	
//----------------------------------------//

	$('#filterbutton').click(function() {

		if (isEmpty($('#filtertext').val())) {
			showMessage("Filtro inválido.<br>A carga de dados será feita sem nenhum filtro.", "redirect", "home.php?action=publicidadeImagens", "long size");
		} else {
			var field = $("#filterfield option:selected").val();
			var text = $("#filtertext").val();
			var desc = $("#desc").val();
			location.href = "home.php?action=publicidadeImagens&filterfield="+field+"&filtertext="+text+"&desc="+desc;
		}
		
	});

//----------------------------------------//

});

//----------------------------------------//

function newPage(currentPage, filterField, filterText, desc) {
	location.href = "home.php?action=publicidadeImagens&filterfield="+filterField+"&filtertext="+filterText+"&desc="+desc+"&page="+currentPage;
}

//----------------------------------------//
