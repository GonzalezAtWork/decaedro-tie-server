//----------------------------------------//	

$(document).ready(function() {

//----------------------------------------//

	$("#new").click(function() {
		location.href="home.php?action=vistorias_edit";
	});

//----------------------------------------//

	$("#insert").click(function() {

		var totAbrig = 0;
		$("input[name='qtd_roteiros[]']").each(function () {
			totAbrig += Number( $(this).val() );
		});

		/*
		if( totAbrig == 'NaN' || totAbrig > 80 ){
			alert('O limite de Pontos de Parada é de 80.');
			return;
		}
		*/

		var tipos = [];
		$("input[name='tipos[]']").each(function () {
			var valor = "";
			if($(this).attr('checked')){
				valor = $(this).val();
				tipos.push(valor);
			}
		});

		var roteiros = [];
		$("input[name='roteiros[]']").each(function () {
			var valor = "";
			if ($(this).attr('checked')) {
				valor = $(this).val();
			}
			roteiros.push(valor);
		});

		var qtd_roteiros = [];
		$("input[name='qtd_roteiros[]']").each(function () {
			qtd_roteiros.push($(this).val());
		});

		if( $('#periodo').val() == "" || $('#data').val() == "" || $("#id_equipe").val() == ""){
			alert('Preencha os campos obrigatórios.');
			return;
		}
		
		$("#contents").css("cursor","wait");
		$.ajax({
			type: "POST",
			url: "ajax/vistorias_insert.php",
			data: {
				id_vistoria:	$('#id_vistoria').val(),
				id_gravidade:	$('#id_gravidade').val(),
				data:			   $('#data').val(),
				periodo:		   $('#periodo').val(),
				tipos:			tipos,
				equipes:		   $("#id_equipe").val(),
				roteiros:		roteiros,
				qtd_roteiros:	qtd_roteiros
			},
			success: function(response) { if (!isJSON(response)) { return false };
				var obj = eval( '(' + response + ')' );
				showMessage("Vistoria inserida com sucesso.", "redirect", "home.php?action=vistorias_edit&id_vistoria=" + obj['id_vistoria']);
			},
			error: function( xhr, textStatus, errorThrown ){
				alert( xhr );
			}
		});
		$("#contents").css("cursor","auto");
		
	});
//----------------------------------------//

	$("#exec").click(function() {
		location.href="home.php?action=vistoriasGuiaPreenche&id_vistoria=" + $('#id_vistoria').val();
	});

//----------------------------------------//

	$('#schedule').click(function() {

		//Salva vistoria antes de continuar
		$('#save').click();

		var errorMessage = "";
		var equipes = $("#id_equipe").val();

		if(isEmpty(equipes)){
			errorMessage = "Escolha o encarregado para esta vistoria.";
		}

		if (isEmpty(errorMessage)) {
			$.ajax({
				type:"POST",
				url:"ajax/vistorias_schedule.php",
				data:{id_vistoria: $('#id_vistoria').val()},
				success: function(response) { if (!isJSON(response)) { return false };
					showMessage("Vistoria agendada com sucesso.", "redirect", "home.php?action=vistorias");
				}
			});		
		} else {
			errorMessage = '<div id="errorMessage"><div align="center" style="margin-top:32px;">É necessário salvar a vistoria primeiro.</div></div>';
			TINY.box.show({html:errorMessage,autohide:4,close:true,animate:true,width:480,height:100})
			$("#contents").css("cursor","auto");
		}

	});

//----------------------------------------//

	$('#cria_guia').click(function() {
		window.open('actions/vistoriasGuia.php?id_vistoria=' + $('#id_vistoria').val(), "poop", "height=500,width=820,modal=yes,alwaysRaised=yes");
	});

//----------------------------------------//		

	$('#delete').click(function() {
		
		$("#contents").css("cursor","wait");
		
		var pesquisa_id_vistoria = $('input[name=pesquisa_id_vistoria]:checked').val();
		if ($.type(pesquisa_id_vistoria) === "undefined") {
			pesquisa_id_vistoria = $('#id_vistoria').val();
		}
		if ($.type(pesquisa_id_vistoria) === "undefined") {	
			showMessage("Nenhuma vistoria selecionada.");
		} else {
			$.ajax({
				type:"POST",
				url:"ajax/vistorias_delete.php",
				data:{id_vistoria:pesquisa_id_vistoria},
				success: function(response) { if (!isJSON(response)) { return false };
					showMessage("Vistoria cancelada com sucesso.", "redirect", "home.php?action=vistorias");
				}
			});
		}

		$("#contents").css("cursor","auto");

	});

//----------------------------------------//

	$('#edit').click(function() {

		var id_vistoria = $('input[name=id_vistoria]:checked').val();

		if ($.type(id_vistoria) === "undefined") {
			showMessage("Nenhuma vistoria selecionada.", "focus", "nome");
		} else {
			location.href='home.php?action=vistorias_edit&id_vistoria=' + id_vistoria;
		}

	});

//----------------------------------------//
	
	$("#save").click(function() {
		
		$("#contents").css("cursor","wait");

		var errorMessage = '';
		var height = 60;

		//Data
		var data = $("#data").val();
		if (isEmpty(data)){
			errorMessage += '<div style="margin-bottom:12px;">- Selecione a data;</div>';
			height += 50;
		}

		//Período
		var periodo = $("#periodo").val();
		if (isEmpty(periodo)){
			errorMessage += '<div style="margin-bottom:12px;">- Selecione o período;</div>';
			height += 50;
		}

		//Referencia
		var referencia = $("#id_gravidade").val();
		if (isEmpty(referencia)){
			errorMessage += '<div style="margin-bottom:12px;">- Selecione a referência;</div>';
			height += 50;
		}

		//Encarregado
		var equipes = $("#id_equipe").val();
		if (isEmpty(equipes)){
			errorMessage += '<div style="margin-bottom:12px;">- Selecione o supervisor;</div>';
			height += 50;
		}

		//Lista_pontos
		var lista_pontos = [];
		var lis = $('#sortable1').find('li')
		for ( a = 0; a < lis.length ; a++)
		{
			if( lis[a].getAttribute('update') == 'true' ){
				lista_pontos.push( lis[a].getAttribute('id_ponto') )
			}
		}
		if (lista_pontos.length === 0) {
			errorMessage += '<div style="margin-bottom:12px;">- Insira ao menos um abrigo;</div>';
			height += 50;
		}

		//Validando
		if (isEmpty(errorMessage)) {

			$.ajax({
				type: "POST",
				url: "ajax/vistorias_update.php",
				data: {
					id_vistoria:	$('#id_vistoria').val(),
					id_gravidade:	$('#id_gravidade').val(),
					data:			$('#data').val(),
					periodo:		$('#periodo').val(),
					equipes:		equipes,
					lista_pontos:	lista_pontos
				},
				success: function(response) { if (!isJSON(response)) { return false };
					var message = 'Registro salvo com sucesso!';
					str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
					TINY.box.show({html:str, autohide:false, close:true, animate:true, width:380, height:300, closejs:function(){
						window.location.reload();

					} })
				}
			});

		} else {
			errorMessage = '<div id="errorMessage"><div align="center">Os seguintes erros foram encontrados:</div><br><div>' + errorMessage + '</div></div>';
			TINY.box.show({html:errorMessage,autohide:false,close:true,animate:true,width:480,height:height})
		}

		$("#contents").css("cursor","auto");

	});

//----------------------------------------//		

	$('#save_execucao').click(function() {
		salvaGuia(this.name);
	});

	$('#close').click(function() {
		salvaGuia(this.name);
	});

//----------------------------------------//	

});

function salvaGuia(acao){

		$("#contents").css("cursor","wait");
		
		var __data = {
			acao:		acao,
			id_vistoria:$('#id_vistoria').val(),
			km_saida:	$('#km_saida').val(),
			km_chegada:	$('#km_chegada').val(),
			km_rodados:	$('#km_rodados').val(),
			hs_saida:	$('#hs_saida').val(),
			hs_chegada:	$('#hs_chegada').val(),
			hs_rodados:	$('#hs_rodados').val()
		};
		var pontos = [];
		$("input[name='pontos[]']").each(function ()
		{
			var valor = "";
			if($(this).attr('checked')){
				valor = $(this).val() + '';
				pontos.push(valor);
				__data["itensVistoria_"+ valor] = $('[name=itensVistoria_'+ valor +']:checked').map(function () { return this.value; }).get().join(","); 
				__data["observacaovistoria_"+ valor] = $('#observacaovistoria_'+ valor + '').val();
			}
		});
		__data['pontos'] = pontos;

		var gerar_os = [];
		$("input[name='gerar_os[]']").each(function ()
		{
			var valor = "";
			if($(this).attr('checked')){
				valor = $(this).val() + '';
				gerar_os.push(valor);
			}
		});
		__data['gerar_os'] = gerar_os;

		$.ajax({
			type:"POST",
			url:"ajax/vistorias_guia_update.php",
			data: __data,
			success: function(response) { if (!isJSON(response)) { return false };
				if(acao == 'save_execucao'){
					showMessage("Vistoria salva com sucesso.", "redirect", "home.php?action=vistoriasGuiaPreenche&id_vistoria="+$('#id_vistoria').val());
				}else{
					showMessage("Vistoria finalizada com sucesso.", "redirect", "home.php?action=vistorias");
				}
			}
		});
		
		$("#contents").css("cursor","auto");
}

//----------------------------------------//

function checa_simak(){
	$.ajax({
		type: "POST",
		url: "ajax/pontos_buscaSimak.php",
		data: {
			simak:	$('#simak').val()
		},
		success: function(response) { if (!isJSON(response)) { return false };
			if(response != "[]"){
				var obj = eval( '(' + response + ')' )[0];
				var message = '<h1 style="color:#005599;">Ponto de Parada:</h1>';
				message += '<h2 align="left">';
				message += "&nbsp;&nbsp;<b>Simak:</b> " + obj['codigo_abrigo'] + "<br/>";
				message += "&nbsp;&nbsp;<b>Endereço:</b> " + obj['endereco'] + "<br/>";
				message += "&nbsp;&nbsp;<b>Roteiro:</b> " + obj['roteiro'] + "<br/>";
				message += '<center><input type="button" value="Cancelar" onclick="TINY.box.hide()"/>';
				message += '<input type="button" value="Inserir" onclick="';
				message += "inserirSimak('" + obj['id_ponto'] + "','" + obj['codigo_abrigo'] + "','" + obj['endereco'] + "','" + obj['id_roteiro'] + "','" + obj['roteiro'] + "')";
				message += '"></center>';
				message += '</h2>';
				str = '' + message + '';
			}else{
				str = '<h1 style="color:#005599;">Simak não encontrado.</h1>';
			}
			TINY.box.show({html:str, close:true, animate:true, width:380, height:150 })
			$("#contents").css("cursor","auto");
		}
	});
}

function inserirSimak(id_ponto, codigo_abrigo, endereco,id_roteiro, roteiro ){
	$.ajax({
		type: "POST",
		url: "ajax/vistorias_insert_ponto.php",
		data: {
			id_ponto: id_ponto,
			id_vistoria: $('#id_vistoria').val()
		},
		success: function(response) { if (!isJSON(response)) { return false };
			location.href="home.php?action=vistorias_edit&id_vistoria=" + $('#id_vistoria').val();
		}
	});
}


function checa_roteiro() {
	var message = '';
	message += '<h1 style="color:#005599;padding-top:8px;">Atribuição de Roteiro</h1>';

	message += '<h2 align="left" style="clear:both; float:left;">';
	message += "<span style='float:left;text-align:right;width:90px;padding-top:20px;'><b>Roteiro:</b></span>";
	message += "<span style='float:left;text-align:right;width:90px;padding:16px 0px 0px 8px;'><select name='pop_id_roteiro' id='pop_id_roteiro'>"+$('#p_id_roteiro').html()+"</select></span>";
	message += '</h2>';

	message += '<h2 align="left" style="clear: both; float:left;">';
	message += "<span style='float:left;text-align:right;width:90px;padding-top:4px;'><b>Bairro:</b></span>";
	message += "<span style='float:left;text-align:right;width:90px;padding-left:8px;'><select name='pop_id_bairro' id='pop_id_bairro'>"+ $('#p_id_bairro').html() +"</select></span>";
	message += '</h2>';

	message += '<h2 align="left" style="clear: both; float:left;">';
	message += "<span style='float:left;text-align:right;width:90px;padding-top:4px;'><b>Tipo:</b></span>";
	message += "<span style='float:left;text-align:right;width:90px;padding-left:8px;'><select name='pop_id_tipo' id='pop_id_tipo'>"+ $('#p_id_tipo').html() +"</select></span>";
	message += '</h2>';

	message += '<div style="clear:both;text-align:center;">';
	message += '<input type="button" value="Cancelar" onclick="TINY.box.hide()"/>';
	message += '<input type="button" value="Inserir" onclick="';
	message += "inserirRoteiro( $('#pop_id_roteiro').val(), $('#pop_id_tipo').val(), $('#pop_id_bairro').val() )";
	message += '">';
	message += '</div>';

	str = '' + message + '';

	TINY.box.show({html:str, close:true, animate:true, width:480, height:250 })
}

function inserirRoteiro(id_roteiro, id_tipo, id_bairro ) {

	if (id_roteiro != "" && id_tipo != "" ) {
		$.ajax({
			type: "POST",
			url: "ajax/vistorias_insert_roteiro.php",
			data: {
				id_vistoria: $('#id_vistoria').val(),
				 id_roteiro: id_roteiro,
				    id_tipo: id_tipo,
					id_bairro: id_bairro
			},
			success: function(response) {
				if (!isJSON(response)) {
					return false
				} else {
					location.href="home.php?action=vistorias_edit&id_vistoria=" + $('#id_vistoria').val();
				}
			}
		});
	}
}