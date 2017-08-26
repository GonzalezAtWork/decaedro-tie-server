//----------------------------------------//	

$(document).ready(function() {
	
//----------------------------------------//			

	$("#insert").click(function() {
		//alert($('#id_usuario').val() +' - '+ $('#id_ponto').val() +' - '+ $('#id_status').val());
		if ( isEmpty( $('#id_status').val() ) ) {
			errorMessage = '<div class="item">&#8226;&nbsp;Escolha um Status;</div>';
			//TINY.box.show({html:errorMessage,autohide:4,close:true,animate:true,width:480,height:height});
			//$("#contents").css("cursor","auto");
		} else {
			$("#contents").css("cursor","wait");
			$.ajax({
				type: "POST",
				url: "ajax/pontosStatusHistorico_insert.php",
				data: {
					id_usuario: $('#id_usuario').val(),
					id_ponto: $('#id_ponto').val(),
					id_status: $('#id_status').val()
				},
				success: function(response) { if (!isJSON(response)) { return false };
					//alert(response);
					var message = 'Status inserido com sucesso!';
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
});

//----------------------------------------//