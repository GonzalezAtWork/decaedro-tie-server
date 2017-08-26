$(document).ready(function() {

//----------------------------------------//	
	if (window.File && window.FileReader && window.FileList && window.Blob) {
		// Great success! All the File APIs are supported.
	} else {
		alert('Browser não suportado.');
	}
	document.getElementById('files').addEventListener('change', handleFileSelect, false);

//----------------------------------------//
	$('#enviar').click(function() {

		$("#contents").css("cursor","wait");

		$.ajax({
			type: "POST",
			url: "ajax/publicidade_import.php",
			data: {
				id_usuario: $('#id_usuario').val(),
				csv: document.getElementById('list').innerHTML
			},
			success: function(response) {if (!isJSON(response)) { return false };
				var message = '';
				if(response == '{"processado":"true"}'){
					message = 'Cartazes incluídos!';
				}else{
					message = 'Erro na importação!';
				}
				str = '<h1 style="color:#005599;height:140px;line-height:140px;">' + message + '</h1>';
				TINY.box.show({html:str, autohide:4, close:true, animate:true, width:380, height:150, closejs:function(){location.href='home.php?action=publicidade';} })
				$("#contents").css("cursor","auto");
			},
			error: function(jqXHR, textStatus, errorThrow){
				console.log(textStatus + ': '+ errorThrow + " - " + jqXHR.responseText);
			}
		});
	});
//----------------------------------------//	
});

function handleFileSelect(evt) {
	var files = evt.target.files;
	var reader = new FileReader();
	var f = files[0];
	reader.onload = (function(theFile) {
		return function(e) {
			document.getElementById('list').innerHTML = e.target.result;
		};
	})(f);
	reader.readAsText(f);
}