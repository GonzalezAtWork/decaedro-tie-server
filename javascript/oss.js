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
	$("#publicidade").click(function() {
		location.href="home.php?action=oss_new&publicidade=OK";
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

		var pesquisa_id_os = $('input[name=pesquisa_id_os]:checked').val();
		
		if ($.type(pesquisa_id_os) === "undefined") {
			
			showMessage("Nenhuma Os selecionada.", "focus", "nome");
			
		} else {
			
			location.href='home.php?action=oss_edit&id_os=' + pesquisa_id_os;
			
		}
		
	});


//----------------------------------------//
	
	$("#save").click(function() {
		
		//$("#contents").css("cursor","wait");
		var ocorrencias = [];
		$("input[name='ocorrencias[]']").each(function ()
		{
			var valor = "";
			if($(this).attr('checked')){
				valor = $(this).val();
				ocorrencias.push(valor);
			}
		});	
		if(ocorrencias.length == 0){
			ocorrencias = "";
		}
		$.ajax({
			type: "POST",
			url: "ajax/oss_update.php",
			data: {
				id_os:	$('#id_os').val(),
				ocorrencias:	ocorrencias
			},
			success: function(response) { if (!isJSON(response)) { return false };
				//prompt('',response);
				var message = 'Alterações salvas com sucesso!';
				str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){
					window.location.reload();
				} })
				$("#contents").css("cursor","auto");
			}
		});

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