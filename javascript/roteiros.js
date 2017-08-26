//----------------------------------------//	

$(document).ready(function() {
	//----------------------------------------//			

	$("#google").click(function() {
		var link = "https://maps.google.com/maps?";
		for (var i = 0; i < 20; i++) {
			if(i == 0){
				link += 'saddr=';
			}else if(i == 1){
				link += '&daddr=';
			}else{
				link += '+to:';
			}
			link += enderecos_tipo[i].lat +','+ enderecos_tipo[i].long;
		}
		window.open(link,'_blank');
	});

//----------------------------------------//			

	$("#reset").click(function() {
		$.ajax({
			type: "POST",
			url: "ajax/roteiros_update.php",
			data: {
				id_roteiro:$('#id_roteiro').val(),
				reset: 'OK'
			},
			success: function(response) { if (!isJSON(response)) { return false };
				var message = 'Listagem re-ordenada com sucesso!';
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
	});
//----------------------------------------//			

	$("#mapa").click(function() {
		location.href="home.php?action=roteirosMapa";
	});
//----------------------------------------//			

	$("#insert").click(function() {
		
		$("#contents").css("cursor","wait");

		if (isEmpty($('#nome').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;Nome;</div>';
		} else {
			$.ajax({
				type: "POST",
				url: "ajax/roteiros_insert.php",
				data: {
					nome: $('#nome').val()
				},
				success: function(response) { if (!isJSON(response)) { return false };
					//alert(response);
					var message = 'Roteiro inserido com sucesso!';
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
		var pesquisa_id_roteiro = $('input[name=pesquisa_id_roteiro]:checked').val();
		//alert(pesquisa_id_roteiro);
		$.ajax({
			type: "POST",
			url: "ajax/roteiros_delete.php",
			data: { id_roteiro:pesquisa_id_roteiro},
			success: function(response) { if (!isJSON(response)) { return false };
				//alert(response);
				var message = 'Roteiro excluído!';
				str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){window.location.reload()} })
			}
		});
	});

//----------------------------------------//
	$('#edit').click(function() {
		var pesquisa_id_roteiro = $('input[name=pesquisa_id_roteiro]:checked').val();
		// confirmar se vai usar querystring mesmo nisso!
		location.href='home.php?action=roteiros_edit&id_roteiro=' + pesquisa_id_roteiro;
	});
//----------------------------------------//		
	$('#gera_vistoria').click(function() {
		salva(this.name);
	});
//----------------------------------------//	
	$("#save").click(function() {
		salva(this.name);
	});
//----------------------------------------//	
});

//----------------------------------------//

function mostraZona(id_zona){
	document.getElementById('zona_' + id_zona).style.display = (document.getElementById('zona_' + id_zona).style.display =='block' )?'none':'block';
}

function salva(tipo) {
	$("#contents").css("cursor","wait");

	var errorMessage = '';
	var height = 0;
	

	if (isEmpty($('#nome').val())) {
		errorMessage += '<div class="item">&#8226;&nbsp;Nome;</div>'
		height++;
	}
	if (isEmpty(errorMessage)) {
		
		var valLavagem = $('input[name=lavagem]:checked').val()
		if(valLavagem != 'TRUE'){
			valLavagem = 'FALSE'
		}
		
		var valNoturno = $('input[name=noturno]:checked').val()
		if(valNoturno != 'TRUE'){
			valNoturno = 'FALSE'
		}
		var valVistoria = $('input[name=vistoria]:checked').val()
		if(valVistoria != 'TRUE'){
			valVistoria = 'FALSE'
		}
		var valManutencao = $('input[name=manutencao]:checked').val()
		if(valManutencao != 'TRUE'){
			valManutencao = 'FALSE'
		}
		var valPublicidade = $('input[name=publicidade]:checked').val()
		if(valPublicidade != 'TRUE'){
			valPublicidade = 'FALSE'
		}

		var lista_pontos = [];
		var lis = $('#sortable1').find('li')
		for ( a = 0; a < lis.length ; a++)
		{
			if( lis[a].getAttribute('update') == 'true' ){
				lista_pontos.push( lis[a].getAttribute('id_ponto') )
			}
		}
		$.ajax({
			type: "POST",
			url: "ajax/roteiros_update.php",
			data: {
				id_roteiro:$('#id_roteiro').val(),
				lista_pontos:	lista_pontos,
				nome:$('#nome').val(),
				cor:$('#cor').val().split('#').join(''),
				id_gravidade:$('#id_gravidade').val(),
				lavagem: valLavagem,
				noturno: valNoturno,
				vistoria: valVistoria,
				manutencao: valManutencao,
				publicidade: valPublicidade,
				frequencia: $('#frequencia').val(),
				tipo: tipo
			},
			success: function(response) { if (!isJSON(response)) { return false };
				var message = 'Registro salvo com sucesso!';
				str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){
					if(tipo == 'gera_vistoria'){
						var obj = eval( '(' + response + ')' );
						window.location.href = "home.php?action=vistorias_edit&id_vistoria=" + obj['id_vistoria'] ;
					}else{
						window.location.reload()
					}
				} })
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
}