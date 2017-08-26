//----------------------------------------//	

$(document).ready(function() {

//----------------------------------------//		
	$("#lancar_equipes").click(function() {
		TINY.box.show({
			html:strLancaEquipe, close:true, animate:true, width:360, height:180 
		});
	});
	$("#new").click(function() {
		location.href="home.php?action=oss_new";
	});
	$("#critic").click(function() {
		location.href="home.php?action=oss_new&critica=OK";
	});
	$("#insert").click(function() {
		$("#contents").css("cursor","wait");
		$.ajax({
			type: "POST",
			url: "ajax/oss_insert.php",
			data: {
				data:			$('#data').val(),
				chuva:			$('#chuva').val(),
				id_prioridade:	$('#id_prioridade').val(),
				id_gravidade:	$('#id_gravidade').val()
			},
			success: function(response) { if (!isJSON(response)) { return false };
				var obj = eval( '(' + response + ')' );
				showMessage("Os inserida com sucesso.", "redirect", "home.php?action=oss_edit&id_os=" + obj['id_os']);
			}
		});
		$("#contents").css("cursor","auto");
		
	});
//----------------------------------------//		
	$("#exec").click(function() {
		location.href="home.php?action=ossGuiaPreenche&id_os=" + $('#id_os').val();
	});

//----------------------------------------//		
	$('#schedule').click(function() {
			$.ajax({
				type:"POST",
				url:"ajax/oss_schedule.php",
				data:{id_os: $('#id_os').val()},
				success: function(response) { if (!isJSON(response)) { return false };
					var message = 'Os agendada com sucesso.';
					str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
					TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){
						window.location.reload();
					} })
					$("#contents").css("cursor","auto");
				}
			});		
	});
//----------------------------------------//		
	$('#cria_guia').click(function() {
		window.open('actions/ossGuia.php?id_os=' + $('#id_os').val(), "poop", "height=500,width=820,modal=yes,alwaysRaised=yes");
	});
//----------------------------------------//		

	$('#delete').click(function() {
		
		$("#contents").css("cursor","wait");
		
		var pesquisa_id_os = $('input[name=pesquisa_id_os]:checked').val();
		if ($.type(pesquisa_id_os) === "undefined") {
			pesquisa_id_os = $('#id_os').val();
		}
		if ($.type(pesquisa_id_os) === "undefined") {	
			showMessage("Nenhuma Os selecionada.");
		} else {
			$.ajax({
				type:"POST",
				url:"ajax/oss_delete.php",
				data:{id_os:pesquisa_id_os},
				success: function(response) { if (!isJSON(response)) { return false };
					showMessage("Os cancelada com sucesso.", "redirect", "home.php?action=oss");
				}
			});
		}

		$("#contents").css("cursor","auto");

	});

//----------------------------------------//
	$('#edit').click(function() {
		var pesquisa_id_ocorrencia = $('input[name=pesquisa_id_ocorrencia]:checked').val();
		if ($.type(pesquisa_id_ocorrencia) === "undefined") {
			showMessage("Nenhuma ocorrência selecionada.", "focus", "nome");
		} else {
			location.href='home.php?action=ocorrencias_edit&id_ocorrencia=' + pesquisa_id_ocorrencia;	
		}
	});
//----------------------------------------//
	
	$("#save").click(salvar_ocorrencia);
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
			id_os:$('#id_os').val(),
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
				__data["itensOs_"+ valor] = $('[name=itensOs_'+ valor +']:checked').map(function () { return this.value; }).get().join(","); 
				__data["observacao_"+ valor] = $('#observacao_'+ valor + '').val();
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
			url:"ajax/oss_guia_update.php",
			data: __data,
			success: function(response) { if (!isJSON(response)) { return false };
				if(acao == 'save_execucao'){
					showMessage("Os salva com sucesso.", "redirect", "home.php?action=ossGuiaPreenche&id_os="+$('#id_os').val());
				}else{
					showMessage("Os finalizada com sucesso.", "redirect", "home.php?action=oss");
				}
			}
		});
		
		$("#contents").css("cursor","auto");
}

//----------------------------------------//

function salvar_ocorrencia() {
	var itensVistoria = [];
	var lis = $('#sortable2').find('li')
	for ( a = 0; a < lis.length ; a++) {
		itensVistoria.push( lis[a].getAttribute('id') )
	}
	if(itensVistoria.length == 0) itensVistoria.push('');
	var itensManutencao = [];
	var lis = $('#sortable4').find('li')
	for ( a = 0; a < lis.length ; a++) {
		itensManutencao.push( lis[a].getAttribute('id') )
	}
	if(itensManutencao.length == 0) itensManutencao.push('');
	var url ="";
	if($('#id_ocorrencia').val() == "0"){
		url = "ajax/ocorrencia_insert.php";
	}else{
		url = "ajax/ocorrencia_update.php";
	}
	//var msg = url + "?id_usuario="+ $('#id_usuario').val() +"&id_ocorrencia="+ $('#id_ocorrencia').val()+"&id_ponto="+ $('#id_ponto').val()+"&executada="+ getCheckBox('executada') +"&observacao="+ $('#observacao').val()+"&id_equipe="+ $('#id_equipe').val()+"&itensVistoria="+ itensVistoria+"&observacaoVistoria="+ $('#observacaoVistoria').val()+"&itensManutencao="+ itensManutencao+"&observacaoManutencao="+ $('#observacaoManutencao').val();
	//prompt('',msg);
	if( typeof( $('#id_usuario').val() ) == "undefined"){
		txt_id_usuario = "0";
	}else{
		txt_id_usuario = $('#id_usuario').val();
	}
	$.ajax({
		type: "POST",
		url: url,
		data: {
			id_usuario:				txt_id_usuario,
			id_ocorrencia:			$('#id_ocorrencia').val(),
			id_ponto:				$('#id_ponto').val(),
			vistoriada:				getCheckBox('vistoriada'),
			executada:				getCheckBox('executada'),
			observacao:				$('#observacao').val(),
			id_equipe:				$('#id_equipe').val(),
			itensVistoria:			itensVistoria,
			observacaoVistoria:		$('#observacaoVistoria').val(),
			itensManutencao:		itensManutencao,
			observacaoManutencao:	$('#observacaoManutencao').val()
		},
		success: function(response) {
			alert( response );				
			if (!isJSON(response)) { return false };
			var obj = eval( '(' + response + ')' );
			var message = 'Alterações salvas com sucesso!';
			str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
			if(obj == null){
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){
					if( $('#retorno_id_os').val() != "" ){
						location.href="home.php?action=oss_edit&id_os=" + $('#retorno_id_os').val();
					}else{
						window.location.reload();
					}
				} })
			}else{
				showMessage("Ocorrência inserida com sucesso.", "redirect", "home.php?action=ocorrencias_edit&id_ocorrencia=" + obj['id_ocorrencia']);
			}
			$("#contents").css("cursor","auto");
		}, 
		error: function(jqXHR, textStatus, errorThrow){
			console.log(textStatus + ': '+ errorThrow + " - " + jqXHR.responseText);
		}
	});

}
function mostraTela(tipo){
	document.getElementById('dados').style.display = 'none';
	document.getElementById('vistoria').style.display = 'none';
	document.getElementById('manutencao').style.display = 'none';

	document.getElementById(tipo).style.display = 'block';
}
function LancaEquipes(){
	if($('#qtd').val() == "" || $('#id_roteiro').val() == "" || $('#id_os').val() == "" ){
		alert('Preencha todos os campos');
		return;
	}
	$.ajax({
			type: "POST",
			url: "ajax/oss_lancaequipe.php",
			data: {
				id_os:		$('#id_os').val(),
				id_equipe:	$('#id_equipe').val(),
				id_roteiro: $('#id_roteiro').val(),
				qtd:		$('#qtd').val()
			},
			success: function(response) { if (!isJSON(response)) { return false };
				var message = 'Equipes lançadas com sucesso!';
				str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){
					window.location.reload();
				} })
				$("#contents").css("cursor","auto");
			}
		});
}
function trocaEquipe(id_ocorrencia, simak, endereco, roteiro, equipe){
	strFinal = strTrocaEquipe;
	strFinal = strFinal.split('**tr_id_ocorrencia**').join(id_ocorrencia);
	strFinal = strFinal.split('**tr_codigo**').join(simak);
	strFinal = strFinal.split('**tr_nome**').join(endereco);
	strFinal = strFinal.split('**tr_roteiro**').join(roteiro);
	strFinal = strFinal.split('<option value="'+ equipe +'">').join('<option selected value="'+ equipe +'">');
	TINY.box.show({
		html:strFinal, close:true, animate:true, width:360, height:230 
	});
}
function execTrocaEquipe(){
	$.ajax({
			type: "POST",
			url: "ajax/oss_trocaequipe.php",
			data: {
				id_ocorrencia:	$('#tr_id_ocorrencia').val(),
				id_equipe:		$('#tr_id_equipe').val()
			},
			success: function(response) { if (!isJSON(response)) { return false };
				var message = 'Equipes alterada com sucesso!';
				str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){
					window.location.reload();
				} })
				$("#contents").css("cursor","auto");
			}
		});
}

function getCheckBox( campo ){
	var val = $('input[name=' + campo + ']:checked').val()
	if(val != 'TRUE'){
		val = 'FALSE'
	}
	return val;
}
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
				message += '<center><input type="button" value="OK" onclick="';
				message += "alteraSimak('" + obj['id_ponto'] + "','" + obj['codigo_abrigo'] + "','" + obj['endereco'] + "','" + obj['id_roteiro'] + "','" + obj['roteiro'] + "')";
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
function alteraSimak(id_ponto, codigo_abrigo, endereco,id_roteiro, roteiro ){
	$('#id_ponto').val(id_ponto);
	$('#simak').val(codigo_abrigo);
	$('#endereco').val(endereco);
	$('#id_roteiro').val(id_roteiro);
	$('#roteiro').val(roteiro);
	TINY.box.hide();
}