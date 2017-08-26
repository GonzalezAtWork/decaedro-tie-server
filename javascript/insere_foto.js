//----------------------------------------//	

$(document).ready(function() {

//----------------------------------------//		

	$('#save').click(function() {
		$("#contents").css("cursor","wait");
		$.ajax({
			type: "POST",
			url: "ajax/insere_foto.php",
			data: {
				stamp: $('#stamp').val(),
				nome: $('#nome').val(),
				data: $('#data').val(),
				id_ponto: $('#id_ponto').val(),
				id_ocorrencia: $('#id_ocorrencia').val(),
				id_os: $('#id_os').val(),
				id_vistoria: $('#id_vistoria').val(),
				id_item: $('#id_item').val(),
				foto: $('#foto').val()
			},
			success: function(response) { if (!isJSON(response)) { return false };
				//alert(response);
				var message = 'Foto incluída com sucesso!';
				str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){window.location.reload()} })
			},
			error: function(jqXHR, textStatus, errorThrow){
				alert(textStatus + ': '+ errorThrow );
			}
		});
	});

//----------------------------------------//

	$("#file_foto").change(mudaImagem);
	
	var b64;
	var img;

	function mudaImagem (e) {
		img = document.getElementById(this.name.split('file_').join('bmp_'))
		b64 = document.getElementById(this.name.split('file_').join(''))
		var file = this.files[0],
		reader = new FileReader();
			
		reader.onload = function (event) {
			document.getElementById('stamp').value = new Date().getTime();
			document.getElementById('nome').value = file.name.split('.jpg').join('');
			document.getElementById('data').value = file.lastModifiedDate.toISOString().split('T').join(' ').split('Z')[0];
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

