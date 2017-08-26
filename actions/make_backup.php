<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$query = " select count(1) as total from fotografias ";
$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultSet();
$qtd_fotos = 50;
$total = 0;
foreach ($db_result as $row) {
	$total = $row["total"];
}
//$total = 1500;
?>

<script type="text/javascript" src="includes/jszip.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script language="javascript">
	var zip;
	var files = [];
	var data;
	var msg = "";
	var variacao = 0;
	function create_zip() {
		data = new Date().getTime();
		errorMessage = '<div id="errorMessage"><div align="center">&nbsp;</div><div align="center">Processando...</div></div>';
		TINY.box.show({html:errorMessage,close:true,animate:true,width:480,height:100});
		setTimeout(exec_create_zip,1000);
	}
	function exec_create_zip() {
		zip = new JSZip();
		files.push("dump_geral.sql|||ajax/dupe_base.php?id_usuario=2");
		/*
		<?php
			for ( $i = 0; $i < ceil($total/$qtd_fotos) ; $i++ ) {
				echo 'files.push("dump_fotos_'. substr("00000".$i, -5) .'.sql|||ajax/dupe_fotos.php?id_usuario=2&limit='. $qtd_fotos .'&offset='. ($i * $qtd_fotos) .'");';
			}
		?>
		*/
		insertfile_zip();
	}
	function insertfile_zip(){
		if(files.length > 0){
			var data_interna =  new Date().getTime();
			var base = files.pop();
			var nome = base.split('|||')[0];
			var path = base.split('|||')[1];
			$.ajax({
				type: "POST",				
				dataType: "text",
				url: path,
				success: function(response) {
					console.log( nome + ": " + (((new Date().getTime()) - data_interna ) /1000).toFixed(2) + " segundos" ) ;
					zip.file(nome,response);
					$('#errorMessage').html('<div align="center">Processando...</div><div align="center">&nbsp;</div><div align="center">'+nome+' Processado</div>');
					insertfile_zip();
				}, 
				error: function(jqXHR, textStatus, errorThrow){
					console.log(textStatus + ': '+ errorThrow + " - " + jqXHR.responseText);
				}
			});
		}else{
			finalize_zip();
		}
	}
	function finalize_zip() {
		var data_interna =  new Date().getTime();
		$('#errorMessage').html('<div align="center">&nbsp;</div><div align="center">Gerando arquivo compactado...</div>');
		var blobLink = document.getElementById('blob');
		blobLink.download = "backup.zip";
		blobLink.href = window.URL.createObjectURL(zip.generate({type:"blob"}));
		TINY.box.hide();
		variacao = (((new Date().getTime()) - data ) /1000)/60;
		msg = "Tempo total de processamento: " + variacao.toFixed(2) + " minutos";
		blobLink.click();		
		console.log( "Compressao: " + (((new Date().getTime()) - data_interna ) /1000).toFixed(2) + " segundos" );
		alert(msg);
	}
</script>
<form name="form" id="form" method="post" action="home.php?action=publicidade_checkin">
	<fieldset id="groups">
		<legend>Gerador de Backup</legend>
		<div align="center" style="padding-top:10px;">
			<input type="button" onclick="create_zip()" name="save" id="save" value="Exportar ZIP"/>
		</div>
	</fieldset>
	<a href="#" id="blob" style="display:none">click to download</a>
</form>