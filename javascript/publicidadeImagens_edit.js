//----------------------------------------//

$(document).ready(function() {

	var currentURL = $(location).attr('href');

	$( "#periodo_inicio" ).datepicker({
		showOn: "button",
		buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "dd-mm-yy"
	});

	$( "#periodo_fim" ).datepicker({
		showOn: "button",
		buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "dd-mm-yy"
	});

	$("#periodo_inicio").focus();

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
		location.href = "home.php?action=publicidadeImagens";
	});

//----------------------------------------//

	$("#save").click(function() {


		if ($('#periodo_inicio').val() == '' ) {

			showMessage('Os campos "período de veiculação" são obrigatórios.', "focus", "periodo_inicio");

		} else if ($('#periodo_fim').val() == '' ) {

			showMessage('Os campos "período de veiculação" são obrigatórios.', "focus", "periodo_fim");

		} else if ($('#imagem').val() == '' && $('#imagem').val() =='data:image/jpeg;base64,') {

			showMessage('O campo "imagem" é obrigatório.', "focus", "imagem");

		} else {

			$.ajax({
				type:"POST",
				url:"ajax/publicidadeImagens_insert.php",
				data:{
					periodo_inicio:$('#periodo_inicio').val(),
					periodo_fim:$('#periodo_fim').val(),
					nome:$('#imagem').val(),
					observacao:$('#observacao').val(),
					imagem:$('#toShow').attr('src')
				},
				success: function(response) {
					if (!isJSON(response)) {
						alert( response );
						return false;
					} else {
						showMessage("Registro inserido com sucesso!", "redirect", "home.php?action=publicidadeImagens");
					}
				}
			});

		}
	});
	
//----------------------------------------//

	$('#filterbutton').click(function() {

		if (isEmpty($('#filtertext').val())) {
			showMessage("Filtro inválido.<br>A carga de dados será feita sem nenhum filtro.", "redirect", "home.php?action=users", "long size");
		} else {
			var field = $("#filterfield option:selected").val();
			var text = $("#filtertext").val();
			var desc = $("#desc").val();
			location.href = "home.php?action=publicidadeImagens&filterfield="+field+"&filtertext="+text+"&desc="+desc;
		}
		
	});

//----------------------------------------//

	$("#imagem").change(function() {

		var img = document.getElementById('toShow');
		var nome = document.getElementById('nome_imagem');
		var file = this.files[0];
		var reader = new FileReader();

		reader.onload = function (event) {
			if (event.target.result && event.target.result.match(/^data:base64/)) {
				img.src = event.target.result.replace(/^data:base64/, 'data:image/jpeg;base64');
			} else {
				img.src = event.target.result;
			}
		};

		reader.readAsDataURL(file);

		nome.value = file.name;

		return false;

	});

//----------------------------------------//

});

//----------------------------------------//

function newPage(currentPage, filterField, filterText, desc) {
	location.href = "home.php?action=publicidadeImagens&filterfield="+filterField+"&filtertext="+filterText+"&desc="+desc+"&page="+currentPage;
}

//----------------------------------------//
