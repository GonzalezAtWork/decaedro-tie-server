//----------------------------------------//	

$(document).ready(function() {

	$("#nome").focus();
	
//----------------------------------------//			

	$("#insert").click(function() {
		
		$("#contents").css("cursor","wait");

		if (isEmpty($('#nome').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;Nome;</div>';
		} else {
			var valTotem = $('input[name=totem]:checked').val()
			if(valTotem != 'TRUE'){
				valTotem = 'FALSE'
			}
			$.ajax({
				type: "POST",
				url: "ajax/pontosTipo_insert.php",
				data: {
					nome: $('#nome').val(),
					totem: valTotem
				},
				success: function(response) { if (!isJSON(response)) { return false };
					//alert(response);
					var message = 'Tipo de Ponto inserido com sucesso!';
					str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
					TINY.box.show({
						html:str,
						autohide:4,
						close:true,
						animate:true,
						width:380,
						height:150,
						closejs:function(){window.location.reload()}
					})
					$("#contents").css("cursor","auto");
				}
			});
		}
		
	});
//----------------------------------------//		

	$('#delete').click(function() {
		$("#contents").css("cursor","wait");
		var pesquisa_id_tipo = $('input[name=pesquisa_id_tipo]:checked').val();
		//alert(pesquisa_id_tipo);
		$.ajax({
			type: "POST",
			url: "ajax/pontosTipo_delete.php",
			data: { id_tipo:pesquisa_id_tipo},
			success: function(response) { if (!isJSON(response)) { return false };
				//alert(response);
				var message = 'Tipo excluído!';
				str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){window.location.reload()} })
			}
		});
	});

//----------------------------------------//
	$('#edit').click(function() {
		var pesquisa_id_tipo = $('input[name=pesquisa_id_tipo]:checked').val();
		// confirmar se vai usar querystring mesmo nisso!
		location.href='home.php?action=pontosTipo_edit&id_tipo=' + pesquisa_id_tipo;
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
			var valTotem = $('input[name=totem]:checked').val()
			if(valTotem != 'TRUE'){
				valTotem = 'FALSE'
			}
			$.ajax({
				type: "POST",
				url: "ajax/pontosTipo_update.php",
				data: {
					id_tipo:$('#id_tipo').val(),
					nome:$('#nome').val(),
					cor:$('#cor').val().split('#').join(''),
					totem: valTotem
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