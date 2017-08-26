//----------------------------------------//	

$(document).ready(function() {


//----------------------------------------//			
	$("#insert").click(function() {
		$("#contents").css("cursor","wait");
		$.ajax({
			type: "POST",
			url: "ajax/bairros_insert.php",
			data: {
				nome: ''
			},
			success: function(response) { if (!isJSON(response)) { return false };
				showMessage("Registro inserido com sucesso.", "redirect", "home.php?action=bairros_edit&id_item="+response);
			}
		});
		$("#contents").css("cursor","auto");
	});
//----------------------------------------//		
	$('#delete').click(function() {
		$("#contents").css("cursor","wait");
		var pesquisa_id = $('input[name=id_bairro]:checked').val();
		if ($.type(pesquisa_id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			$.ajax({
				type:"POST",
				url:"ajax/bairros_delete.php",
				data:{id_bairro: pesquisa_id},
				success: function(response) { if (!isJSON(response)) { return false };
					showMessage("Registro excluído com sucesso.", "redirect", "home.php?action=bairros");
				}
			});
		}
		$("#contents").css("cursor","auto");
	});
//----------------------------------------//
	$('#edit').click(function() {
		var pesquisa_id = $('input[name=id_bairro]:checked').val();	
		if ($.type(pesquisa_id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			location.href='home.php?action=bairros_edit&id_bairro=' + pesquisa_id;
		}
	});

//----------------------------------------//			
	$("#save").click(function() {
		
		$("#contents").css("cursor","wait");

		var errorMessage = '';
		var height = 0;
		
		
		if (isEmpty($('#nome').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;Nome;</div>'
			height++;
		}
		if (isEmpty(errorMessage)) {
			$.ajax({
				type: "POST",
				url: "ajax/bairros_update.php",
				data: {
					id_bairro:$('#id_bairro').val(),
					id_zona:$('#id_zona').val(),
					vistoria:$('#vistoria').val(),
					distancia:$('#distancia').val(),
					nome:$('#nome').val()
				},
				success: function(response) { if (!isJSON(response)) { return false };
					//alert(response);
					var message = 'Registro salvo com sucesso!';
					str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
					TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){window.location.reload()} })
					$("#contents").css("cursor","auto");
				}
			});
		} else {
			var adjust = 0;
			
			switch (height) {
				case 1:
					adjust = -10;
					break;
				case 2 || 3:
					adjust = -5;
					break;
				case 4:
					adjust = 5;
					break;
				case 5 :
					adjust = 8;
					break;
				case 6:
					adjust = 12;
					break;
				case 7:
					adjust = 15;
					break;
				case 8:
					adjust = 15;
					break;
			}
			
			height = ((height+2)*25)+adjust;

			errorMessage = '<div id="errorMessage"><div align="center">Os seguintes campos s\343o inv\341lidos:</div><div>' + errorMessage + '</div></div>';
			
			TINY.box.show({html:errorMessage,autohide:4,close:true,animate:true,width:480,height:height})
			
			$("#contents").css("cursor","auto");
		}
	});
//----------------------------------------//	
});

//----------------------------------------//