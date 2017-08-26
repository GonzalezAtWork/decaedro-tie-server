$(document).ready(function() {

//----------------------------------------//

	$("#week").mask("99");

//----------------------------------------//	

	if (window.File && window.FileReader && window.FileList && window.Blob) {
		// Great success! All the File APIs are supported.
	} else {
		alert('Browser não suportado.');
	}
	document.getElementById('files').addEventListener('change', handleFileSelect, false);

//----------------------------------------//

	$('#import').click(function() {

		$("#contents").css("cursor","wait");

		var week = $('#week').val();
		var file = document.getElementById('fileContents').innerHTML;
		var delimiter = $('#delimiter').val();

		//Verifica validade da semana
		if (isEmpty(week) || week < 1 || week > 52 ) {

			showMessage("Semana inválida.", "focus", "week");

		//Verifica se o arquivo tá vazio
		} else if (isEmpty(file)) {

			showMessage("Arquivo CSV não selecionado.", "focus", "files");

		} else {

			$.ajax({
				type: "POST",
				url: "ajax/publicidadeVeiculacao_import.php",
				data: {
					semana:week,
					csv:file,
					delimitador:delimiter
				},
				success: function(response) {

					if (!isJSON(response)) {

						return false

					} else {

						var objResponse = eval( '(' + response + ')' );

						if (objResponse.processado == "true") {
							showMessage("Dados importados com sucesso.", "redirect", "home.php?action=publicidadeVeiculacao");
						} else {
							showMessage("Erro na importação de dados.<br>" + objResponse.mensagem, "focus", "import", "large");
						}
					}
				},

				error: function(jqXHR, textStatus, errorThrow) {
					console.log(textStatus + ': '+ errorThrow + " - " + jqXHR.responseText);
				}

			});

		}

		$("#contents").css("cursor","auto");

	});
//----------------------------------------//	
});

function handleFileSelect(evt) {

	var files = evt.target.files;
	var reader = new FileReader();
	var f = files[0];

	reader.onload = (function(theFile) {
		return function(e) {
			document.getElementById('fileContents').innerHTML = e.target.result;
		};
	})(f);

	reader.readAsText(f);

}