//----------------------------------------//	

$(document).ready(function() {

//----------------------------------------//			
	$("#insert").click(function() {
		$("#contents").css("cursor","wait");
		$.ajax({
			type: "POST",
			url: "ajax/pontos_insert.php",
			data: {
				endereco: ''
			},
			success: function(response) { if (!isJSON(response)) { return false };
				showMessage("Registro inserido com sucesso.", "redirect", "home.php?action=pontos_edit&id_ponto="  + response);
			}
		});
		$("#contents").css("cursor","auto");
	});

//----------------------------------------//		
	$('#delete').click(function() {
		$("#contents").css("cursor","wait");
		var pesquisa_id = $('input[name=id_ponto]:checked').val();
		if ($.type(pesquisa_id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			$.ajax({
				type:"POST",
				url:"ajax/pontos_delete.php",
				data:{id_ponto: pesquisa_id},
				success: function(response) { if (!isJSON(response)) { return false };
					showMessage("Registro excluído com sucesso.", "redirect", "home.php?action=pontos");
				}
			});
		}
	});
//----------------------------------------//
	$('#edit').click(function() {
		var pesquisa_id = $('input[name=id_ponto]:checked').val();	
		if ($.type(pesquisa_id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			location.href='home.php?action=pontos_edit&id_ponto=' + pesquisa_id;
		}
	});
//----------------------------------------//
	$('#import').click(function() {
		location.href='home.php?action=pontos_import';
	});

//----------------------------------------//			
	$("#save").click(function() {
		
		$("#contents").css("cursor","wait");

		var errorMessage = '';
		var height = 0;

		if (isEmpty($('#endereco').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;Endereço;</div>'
			height++;
		}

		if (isEmpty(errorMessage)) {

			var interferencia_E_codigo = [];
			$("input[name='interferencia_E_codigo[]']").each(function ()
			{
				interferencia_E_codigo.push($(this).val());
			});
			var interferencia_D_codigo = [];
			$("input[name='interferencia_D_codigo[]']").each(function ()
			{
				interferencia_D_codigo.push($(this).val());
			});
			var interferencia_E = [];
			$("input[name='interferencia_E[]']").each(function ()
			{
				interferencia_E.push($(this).val());
			});
			var interferencia_D = [];
			$("input[name='interferencia_D[]']").each(function ()
			{
				interferencia_D.push($(this).val());
			});

			$.ajax({
				type: "POST",
				url: "ajax/pontos_update.php",
				data: {
					id_ponto:$('#id_ponto').val(),
					codigo_abrigo:$('#codigo_abrigo').val(),
					codigo_novo:$('#codigo_novo').val(),
					id_padrao:$('#id_padrao').val(),
					endereco:$('#endereco').val(),
					cep:$('#cep').val(),
					noturno: getCheckBox('noturno'),
					id_regional:$('#id_regional').val(),
					posicao_global: $('#posicao_global').val(),
					id_roteiro:$('#id_roteiro').val(),
					posicao_roteiro: $('#posicao_roteiro').val(),
					id_bairro:$('#id_bairro').val(),
					dt_implantacao:$('#dt_implantacao').val(),
					painel_calcada:getCheckBox('painel_calcada'),
					dt_painel_calcada:$('#dt_painel_calcada').val(),
					observacoes:$('#observacoes').val(),
					conjugados:$('#conjugados').val(),
					id_inclinacao:$('#id_inclinacao').val(),
					id_limite_terreno:$('#id_limite_terreno').val(),
					limite_terreno_obs:$('#limite_terreno_obs').val(),
					id_piso_calcada:$('#id_piso_calcada').val(),
					piso_calcada_obs:$('#piso_calcada_obs').val(),
					poste:getCheckBox('poste'),
					poste_quantos:$('#poste_quantos').val(),
					eletrica:getCheckBox('eletrica'),
					secundario:getCheckBox('secundario'),
					iluminacao_publica:getCheckBox('iluminacao_publica'),
					largura_calcada:$('#largura_calcada').val(),
					distancia_calcada:$('#distancia_calcada').val(),
					gmaps_longitude:$('#gmaps_longitude').val(),
					gmaps_latitude:$('#gmaps_latitude').val(),
					interferencia_E_codigo: interferencia_E_codigo,
					interferencia_D_codigo: interferencia_D_codigo,
					interferencia_E: interferencia_E,
					interferencia_D: interferencia_D,
					croquis: $('#croquis').val()
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
	$('#ver_tela1').click(function(){mostraTela('tela1')});
	$('#ver_tela2').click(function(){mostraTela('tela2')});
	$('#ver_tela3').click(function(){mostraTela('tela3')});
	$('#ver_tela4').click(function(){mostraTela('tela4')});
	$('#ver_tela5').click(function(){mostraTela('tela5')});

//----------------------------------------//

	$("#file_croquis").change(mudaImagem);
	
	var b64;
	var img;

	function mudaImagem (e) {
		img = document.getElementById(this.name.split('file_').join('bmp_'))
		b64 = document.getElementById(this.name.split('file_').join(''))
		var file = this.files[0],
		reader = new FileReader();
			
		reader.onload = function (event) {
			b64.value = event.target.result.split('data:image/jpeg;base64,').join('');
			if (event.target.result && event.target.result.match(/^data:base64/)) {
				img.src = event.target.result.replace(/^data:base64/, 'data:image/jpeg;base64');
			} else {
				img.src = event.target.result;
			}
		};
		
		reader.readAsDataURL(file);

		return false;
	}

	//----------------------------------------//

	$('#filterbutton').click(function() {

		if (isEmpty($('#filtertext').val())) {
			showMessage("Filtro inválido.<br>A carga de dados será feita sem nenhum filtro.", "redirect", "home.php?action=pontos", "long size");
		} else {
			var field = $("#filterfield option:selected").val();
			var text = $("#filtertext").val();
			var desc = $("#desc").val();
			location.href = "home.php?action=pontos&filterfield="+field+"&filtertext="+text+"&desc="+desc;
		}

	});

//----------------------------------------//

});

//----------------------------------------//

function mostraTela(tela){
	document.getElementById('tela1').style.display = 'none';
	document.getElementById('tela2').style.display = 'none';
	document.getElementById('tela3').style.display = 'none';
	document.getElementById('tela4').style.display = 'none';
	document.getElementById('tela5').style.display = 'none';
	document.getElementById(tela).style.display = 'block';
	document.getElementById("titulo").innerHTML = document.getElementById(tela).getAttribute('titulo');
	if (tela == "tela5") {
		initialize();
	}
}

//----------------------------------------//

function getCheckBox(campo) {
	var val = $('input[name=' + campo + ']:checked').val()
	if (val != 'TRUE') {
		val = 'FALSE'
	}
	return val;
}

//----------------------------------------//

function newPage(currentPage, filterField, filterText, desc) {
	location.href = "home.php?action=pontos&filterfield="+filterField+"&filtertext="+filterText+"&desc="+desc+"&page="+currentPage;
}

//----------------------------------------//
