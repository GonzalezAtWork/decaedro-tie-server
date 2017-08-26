//----------------------------------------//	

$(document).ready(function() {

//----------------------------------------//

	//Captura da tecla enter para enviar o login
	$(document).keydown(function(e) {
	    e.stopPropagation();
	    if (e.keyCode === 13) {
	    	$('#save').click();
	    }
	});
	
//----------------------------------------//
	$("#nome").focus();
	
//----------------------------------------//			
	jQuery(function($) {
		$("#cpf").mask("999.999.999-99");
		$("#ddd").mask("99");
	});

//----------------------------------------//			
	$("#gerarsenha").click(function() {	
		$.ajax({
			type: "POST",
			url: "ajax/send_password.php",
			data: {
				cpf: cleanUpCPF($("#cpf").val())
			},
			success: function(updated) {
				showMessage('Senha gerada com sucesso!', 'focus', $("#cpf"))
			}
		});
	});
//----------------------------------------//			
	$("#save").click(function() {
		
		$("#contents").css("cursor","wait");

		var errorMessage = '';
		var height = 0;
		
		
		if (isEmpty($('#cpf').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;CPF;</div>'
			height++;
		}
		
		if (isEmpty($('#nome').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;Nome;</div>';
			height++;
		}
		
		if (isEmpty($('#email').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;E-mail;</div>';
			height++;
		}
		
		if (isEmpty($('#ddd').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;DDD;</div>';
			height++;
		}
		
		if (isEmpty($('#celular').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;Celular;</div>';
			height++;
		}
		
		if (isEmpty(errorMessage)) {
			$.ajax({
				type: "POST",
				url: "ajax/user_update.php",
				data: {
					id_usuario:$('#id_usuario').val(),
					id_perfil:$('#id_perfil').val(),
					cpf:$('#cpf').val(),
					nome:$('#nome').val(),
					email:$('#email').val(),
					ddd:$('#ddd').val(),
					celular:$('#celular').val()
				},
				success: function(updated) {
					//alert(updated);
					showMessage('Usu\341rio salvo com sucesso!');
				}
			});
		} else {
			
			//Soma um no tamanho vertical e multiplica por 18, para ajustar a mensagem
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
			
			TINY.box.show({html:errorMessage,autohide:4,close:true,animate:true,width:480,height:height,closejs:function(){$("#cpf").focus()}})
			
			$("#contents").css("cursor","auto");
			
		}
		
	});
//----------------------------------------//			
	
});

//----------------------------------------//
function checkMail(mail) {
    var expression = new RegExp(/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);
    if (typeof(mail) == "string") {
    	if (expression.test(mail)) {
    		return true;
    	}
    } else if (typeof(mail) == "object") {
    	if (expression.test(mail.value)) {
    		return true; 
        }
    } else {
    	return false;
    }
}
//----------------------------------------//