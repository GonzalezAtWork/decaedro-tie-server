//----------------------------------------//	

$(document).ready(function() {

	

//----------------------------------------//			
	$("#insert").click(function() {
		$("#contents").css("cursor","wait");
		$.ajax({
			type: "POST",
			url: "ajax/pontosPadrao_insert.php",
			data: {
				nome: ''
			},
			success: function(response) { if (!isJSON(response)) { return false };
				showMessage("Registro inserido com sucesso.", "redirect", "home.php?action=pontosPadrao_edit&id_padrao="+response);
			}
		});
		$("#contents").css("cursor","auto");
	});
//----------------------------------------//		
	$('#delete').click(function() {
		$("#contents").css("cursor","wait");
		var pesquisa_id = $('input[name=id_padrao]:checked').val();
		if ($.type(pesquisa_id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			$.ajax({
				type:"POST",
				url:"ajax/pontosPadrao_delete.php",
				data:{id_padrao: pesquisa_id},
				success: function(response) { if (!isJSON(response)) { return false };
					showMessage("Registro excluído com sucesso.", "redirect", "home.php?action=pontosPadrao");
				}
			});
		}
		$("#contents").css("cursor","auto");
	});
//----------------------------------------//
	$('#edit').click(function() {
		var pesquisa_id = $('input[name=id_padrao]:checked').val();	
		if ($.type(pesquisa_id) === "undefined") {
			showMessage("Nenhum registro selecionado.");
		} else {
			location.href='home.php?action=pontosPadrao_edit&id_padrao=' + pesquisa_id;
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
				url: "ajax/pontosPadrao_update.php",
				data: {
					id_padrao:$('#id_padrao').val(),
					id_tipo:$('#id_tipo').val(),
					nome:$('#nome').val(),
					croquis:$('#croquis').val(),
					foto:$('#foto').val()
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
	$("#file_foto").change(mudaImagem);
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
});
