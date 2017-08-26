<?php 
$checkBrowser = strrpos($_SERVER["HTTP_USER_AGENT"], "10edroTie");
if ($checkBrowser === false) {
	//die();
}
header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-language" content="pt-BR">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="cache-control" content="no-cache"/>
		<meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT"/>
		<meta http-equiv="pragma" content="no-cache"/>
		<title>TIE4 - Ocorrências</title>
		<script language="javascript" src="jquery/javascript.js"></script>
		<script language="javascript" src="jquery/mobile.js"></script>
		<link rel="stylesheet" href="jquery/css.css"/>
		<script type="text/javascript" src="includes/geral.js"></script>
		<script type="text/javascript" src="includes/header.js"></script>
		<script type="text/javascript" src="includes/camera.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
         //Se é android
			if(typeof(Android) != 'undefined'){
				$('#bt_usuario').html($('#bt_usuario').html().split('***nome***').join( Android.js_NOMEUSUARIO() ));
				$('#token').val( Android.js_TOKEN() );
				document.getElementById("bt_usuario").style.display="block";
			} else {
				$('#token').val("905d5e02167ddc7b27c017fd06653460");
			}
			alert("1");
			abreTela('nova_ocorrencia');
		});
		</script>
	</head>
	<body>
	<div data-role="page" id="page1" name="page1">
		<!-- Cabeçalho -->
		<div data-theme="c" data-role="header" data-position="fixed" data-tap-toggle="false">
			<a name="bt_usuario" id="bt_usuario" data-role="button" data-icon="info" data-iconpos="left" class="ui-btn-left" style="margin-left: 40px; display:none;">***nome***</a>
			<a name="bt_menu" id="bt_menu" data-role="button" onclick=" menuApp()" data-icon="bars" data-iconpos="left" class="ui-btn-right" style="width:80px;">Menu</a>
			<img width="35px" height="35px" src="images/favicon_128.png" style="margin-top: 2px;"/>
		</div>
		<input type="hidden" id="token" name="token"/>
		<input type="hidden" id="id_usuario" name="id_usuario"/>
		<input type="hidden" id="id_ponto" name="id_ponto"/>
		<!-- Conteúdo -->
		<div name="conteudo" id="conteudo" style="padding:10px"></div>
		<!-- Rodapé -->
		<div id="rodape" name="rodape" data-theme="c" data-role="footer" data-position="fixed" data-tap-toggle="false" style="display:none"></div>
	</div>
	</body>
</html>