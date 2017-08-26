//--------------- Globals ----------------//

var thisAction = 'publicidadeVeiculacao';
var idField = 'id_veiculacao';

//----------------------------------------//

function newPage(currentPage, filterField, filterText, desc) {
	location.href = 'home.php?action=' + thisAction + '&page=' + currentPage + '&filterfield=' + filterField + '&filtertext=' + filterText + '&desc=' + desc;
}

//----------------------------------------//

function changeBlock(block, currentPage, filterField, filterText, desc) {
	location.href = 'home.php?&block=' + block + '&action=' + thisAction + '&page=' + currentPage + '&filterfield=' + filterField + '&filtertext=' + filterText + '&desc=' + desc;
}

//----------------------------------------//

function changeOrderBy(newOrder, oldOrder, filter, desc) {
	if (newOrder == oldOrder) {
		desc = !desc;
	} else {
		desc = 'false';
	}
	location.href = 'home.php?action=' + thisAction + '&filterfield=' + newOrder + '&filtertext=' + filter + '&desc=' + desc;
}

//----------------------------------------//

$(document).ready(function() {

	$('#filtertext').focus();

//----------------------------------------//

	//Evita que o ENTER submeta o formulário
	$('form').on('submit', function(event) {
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

	$('#import').click(function() {

		location.href = 'home.php?action=' + thisAction + '_import';

	});

//----------------------------------------//

	$('#edit').click(function() {

		var id = $('input[name=' + idField + ']:checked').val();

		if ($.type(id) === 'undefined') {
			showMessage('Nenhum registro selecionado.', 'focus', 'filterfield');
		} else {
			location.href='home.php?action=' + thisAction + '_edit&' + idField + '=' + id;
		}

	});

//----------------------------------------//

	$('#insert').click(function() {
		
		location.href = 'home.php?action=' + thisAction + '_edit';
		
	});

//----------------------------------------//

	$('#delete').click(function() {
		
		$('#contents').css('cursor', 'wait');

		var id = $('input[name=' + idField + ']:checked').val();
		
		if ($.type(id) === 'undefined') {
			
			showMessage('Nenhum registro selecionado.', 'focus', 'filterfield');
			
		} else {
			
			$.ajax({
				type:'POST',
				url:'ajax/' + thisAction + '_delete.php',
				data:{actionId:id},
				success: function(response) {
					if (!isJSON(response)) {
						return false;
					} else {
						showMessage('Registro excluído com sucesso.', 'redirect', 'home.php?action=' + thisAction);
					}
				}
			});
		}

		$('#contents').css('cursor', 'auto');
	});

//----------------------------------------//

	$('#export').click(function() {

		$.ajax({
			type:'POST',
			url:'ajax/' + thisAction + '_export.php',
			success: function(response) {

				if (!isJSON(response)) {

					return false;

				} else {

					var objToday =	new Date();
					var curDay =	objToday.getDate();
					var curMonth =	objToday.getMonth();
					var curYear =	objToday.getFullYear();

					$.ajax({
						type: "POST",
						url: "ajax/oss_insert.php",
						data: {
							         data: curYear + '-' + curMonth + '-' + curDay,
							        chuva: false,   //false = Não há previsão de chuva
							id_prioridade:	3,       //3 = Publicidade
							 id_gravidade:	1        //1 = Galpão Cotia
						},
						success: function(response) {
							if (!isJSON(response)) {
								return false
							} else {
								var objResponse = eval( '(' + response + ')' );
								showMessage ('Exportação realizada com sucesso.', 'redirect', 'home.php?action=oss_edit&id_os=' + objResponse.id_os)
							}
						}
					});

				}
			}
		});

	});

//----------------------------------------//

	$('#filterbutton').click(function() {

		if (isEmpty($('#filtertext').val())) {

			showMessage('Filtro inválido.<br>A carga de dados será feita sem nenhum filtro.', 'redirect', 'home.php?action=' + thisAction, 'long size');

		} else {
			var field = $('#filterfield option:selected').val();
			var text = $('#filtertext').val();
			var desc = $('#desc').val();
			location.href = 'home.php?action=' + thisAction + '&filterfield=' + field + '&filtertext=' + text + '&desc=' + desc;
		}
		
	});

//----------------------------------------//

});

//----------------------------------------//
