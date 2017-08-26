//----------------------------------------//	

$(document).ready(function() {

	$("#group_name").focus();
	
//----------------------------------------//			

	$("#insert").click(function() {
		
		$("#contents").css("cursor","wait");

		if (isEmpty($('#group_name').val())) {
			errorMessage += '<div class="item">&#8226;&nbsp;CPF;</div>';
		} else {
		
			$.ajax({
				type: "POST",
				url: "ajax/group_insert.php",
				data: {
					entId:$('#id_perfil').val(),
					groupName:$('#group_name').val()
				},
				success: function(updated) {
					var message = 'Grupo inserido com sucesso!';
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
	$("#delete").click(function() {
		
		$("#contents").css("cursor","wait");

		//Tranforma array de checkboxes em array de javascript
		groupsChecked = new Array();
		$("input[type=checkbox][name='group_id[]']:checked").each(function(){
			groupsChecked.push($(this).val());
		});

		$.ajax({
			type: "POST",
			url: "ajax/group_delete.php",
			data: { entId:$('#id_perfil').val(),
					groupsToDelete:groupsChecked },
			success: function(updated) {
				var message = 'Grupos exclu\355dos com sucesso!';
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
		
		$("#contents").css("cursor","auto");
		
	});
//----------------------------------------//
	
});

//----------------------------------------//