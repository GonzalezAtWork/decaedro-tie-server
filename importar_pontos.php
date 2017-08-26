<html xmlns="http://www.w3.org/1999/xhtml" lang="pt" data-cast-api-enabled="true">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	</head>
	<body>
<?php
$importar = (isset($_REQUEST['importar']))?$_REQUEST['importar']:"";
if($importar == "OK"){
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);

	include('classes/XMLObject.php');
	include('classes/database.php');
	$db = Database::getInstance();

	$sql = "";
	$csv = $_REQUEST['list'];
	$csv = str_replace("'","´", $csv);
	$csv = str_replace('"',"´", $csv);
	$csv = str_replace("-23,","-23.", $csv);
	$csv = str_replace("-46,","-46.", $csv);

	$linhas = explode("\n", $csv);
	$total = 0;
	foreach ($linhas as $linha) {
		if($total > 0){
			$linha = explode(";", $linha);
			$sql .= " UPDATE PONTOS SET ";
				//codigo_abrigo
				$sql .= " codigo_abrigo = '". $linha[0] ."',";
				//endereco
				$sql .= " endereco = '". $linha[1] ."',";
				//novo_numero
				$sql .= " codigo_novo = '". $linha[2] ."',";
				//id_padrao
				$sql .= " id_padrao = ( select id_padrao from pontospadrao where id_tipo in ( select id_tipo from pontostipo where nome ilike '%'". $linha[3] ."'%' ) limit 1 ), ";
				//id_roteiro
				$sql .= " id_roteiro = ( select id_roteiro from roteiros where nome ilike '%'". $linha[4] ."'%' limit 1 ),";
				//gmaps_latitude
				$sql .= " gmaps_latitude = '". $linha[5] ."',";
				//gmaps_longitude
				$sql .= " gmaps_longitude = '". $linha[6] ."'";
			$sql .= " where codigo_abrigo = '". $linha[0] ."';";
			//$sql .= "<br/>";
			$sql .= " INSERT INTO PONTOS (codigo_abrigo, endereco, codigo_novo, id_padrao, id_roteiro, gmaps_latitude, gmaps_longitude) ";
			$sql .= " SELECT ";
				//codigo_abrigo
				$sql .= "'". $linha[0] ."',";
				//endereco
				$sql .= "'". $linha[1] ."',";
				//novo_numero
				$sql .= "'". $linha[2] ."',";
				//id_padrao
				$sql .= "( select id_padrao from pontospadrao where id_tipo in ( select id_tipo from pontostipo where nome ilike '%'". $linha[3] ."'%' ) limit 1 ),";
				//id_roteiro
				$sql .= "( select id_roteiro from roteiros where nome ilike '%'". $linha[4] ."'%' limit 1 ),";
				//gmaps_latitude
				$sql .= "'". $linha[5] ."',";
				//gmaps_longitude
				$sql .= "'". $linha[6] ."'";
			$sql .= " WHERE NOT EXISTS (SELECT 1 FROM pontos WHERE codigo_abrigo = '". $linha[0] ."');";
			//$sql .= "<br/><br/>";
		}
		$total += 1;
	}
	/*
	$db->setQuery($sql);
	$db->execute();
	$dados = $db->getResultSet();
	*/
	echo $sql;
}
?>
		<?php if($importar == "OK"){ ?>
			<h2>Arquivo importado com sucesso!</h2>
		<?php } ?>
		<form name="form1" id="form1" method="post" action="importar_pontos.php">
			<input type="hidden" name="importar" id="importar" value="OK"/>
			<input accept=".csv" type="file" id="files" name="files[]" />
			<textarea id="list" name="list"></textarea>
			<input type="submit" value="Enviar"/>
		</form>
	</body>
	<script> 
		if (window.File && window.FileReader && window.FileList && window.Blob) {
			// Great success! All the File APIs are supported.
		} else {
			alert('Browser não suportado.');
		}
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
		document.getElementById('files').addEventListener('change', handleFileSelect, false);
	</script>
</html>