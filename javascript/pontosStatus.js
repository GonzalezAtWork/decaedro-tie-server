//----------------------------------------//	

$(document).ready(function() {

	$("#nome").focus();

//----------------------------------------//			

	$("#insert").click(function() {
		location.href = "home.php?action=pontosStatus_edit"; 
	});

//----------------------------------------//

	$("#cancel").click(function() {
		location.href = "home.php?action=pontosStatus"; 
	});

//----------------------------------------//		

	$('#delete').click(function() {

		$("#contents").css("cursor","wait");

		var pesquisa_id_status = $('input[name=pesquisa_id_status]:checked').val();

		$.ajax({
			type:"POST",
			url:"ajax/pontosStatus_delete.php",
			data:{id_status:pesquisa_id_status},
			success:function(response) {
            if (!isJSON(response)) { return false };
            showMessage("Status excluído com sucesso.", "redirect", "home.php?action=pontosStatus");
			}
		});
	});

//----------------------------------------//

   $('#edit').click(function() {

      var pesquisa_id_status = $('input[name=pesquisa_id_status]:checked').val();
      
      if ($.type(pesquisa_id_status) === "undefined") {
         showMessage("Nenhum status selecionado.", "focus", "filterfield");
      } else {
         location.href='home.php?action=pontosStatus_edit&id_status=' + pesquisa_id_status;
      }
      
   });

//----------------------------------------//			
	$("#save").click(function() {
		
		$("#contents").css("cursor","wait");

		if (isEmpty($('#nome').val())) {
         showMessage("O campo nome é obrigatório.", "focus", "filterfield");
		} else {

			$.ajax({
				type: "POST",
				url: "ajax/pontosStatus_update.php",
				data: {
					id_status:$('#id_status').val(),
					nome:$('#nome').val(),
					observacoes:$('#observacoes').val()
				},
				success: function(response) {
               if (!isJSON(response)) { return false };
               showMessage("Registro salvo com sucesso!", "redirect", "home.php?action=pontosStatus");

				}
			});
		}
			
		$("#contents").css("cursor","auto");
   });

//----------------------------------------//

   $('#filterbutton').click(function() {

      if ($('#filtertext').val()=='') {
         showMessage("Filtro inválido.<br>A carga de dados será feita sem nenhum filtro.", "redirect", "home.php?action=pontosStatus&filter=none", "long size");
      } else {
         var field = $("#filterfield option:selected").val();
         var text = $("#filtertext").val();
         location.href = "home.php?action=pontosStatus&filterfield="+field+"&filtertext="+text; 
      }
      
   });

//----------------------------------------//

});

//----------------------------------------//