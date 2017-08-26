<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
		
	<head>
		<title>Ajuste de Posição GPS</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	</head>
	<script language="javascript">
		function cancelar(){
			Android.loading_show();
			location.href="ajustar_gps.php";
		}
		function atualizaGPS(){			
			var str_dados = "";
			if(typeof(Android) != "undefined"){
				str_dados = Android.js_getGPS(document.getElementById("simak").value);
			}else{
				str_dados = '{';
				str_dados += '"simak":"'+ document.getElementById("simak").value +'",';
				str_dados += '"latitude":"'+ document.getElementById("gmaps_latitude").value +'",';
				str_dados += '"longitude":"'+ document.getElementById("gmaps_longitude").value +'",';
				str_dados += '"device":"'+ document.getElementById("device").value +'"';
				str_dados += '}';
			}
			if(str_dados != 'Testando: NULO'){
				var dados = eval( '(' + str_dados + ')' );
				document.getElementById("simak").value = dados.simak;
				document.getElementById("gmaps_latitude").value = dados.latitude;
				document.getElementById("gmaps_longitude").value = dados.longitude;
				document.getElementById("device").value = dados.device;
				continua();
			}else{
				alert('GPS Desligado!');
			}
		}
		function continua(){
			if(typeof(Android) != "undefined"){
				Android.loading_show();
			}
			document.form1.submit();
		}
		if(typeof(Android) != "undefined"){
			Android.loading_hide();
		}
	</script>
<body>
<center>
<h1>Ajuste de Posição GPS</h1>
<form name="form1" id="form1" action="ajustar_gps.php" method="post">
<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$html = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	include('classes/XMLObject.php');
	include('classes/database.php');
	#Conexão ao banco de dados
	$db = Database::getInstance();

	$acao  = $_REQUEST["acao"];
	if($acao == "busca"){
		$simak = $_REQUEST["simak"];
		$query = "select * from pontos where codigo_abrigo = '" . $simak . "'";
		$db->setQuery($query);
		$db->execute();
		$dados = $db->getResultSet();
		if($db->getRows() == 0){
			$html .= '<h1>Simak Não Encontrado!</h1><br/>';
			$html .= '<input type="hidden" name="acao" id="acao" value="busca"/>';
			$html .= '<b>Digite o Número Simak:</b><br/>';
			$html .= '<input type="tel" name="simak" id="simak" style="height:40px;width:250px"/><br/>';
			$html .= '<input type="button" onclick="continua()" value="Procurar" style="height:70px;width:250px"/>';
		}
		foreach ($dados as $row) {
			$html .= '<input type="hidden" name="acao" id="acao" value="atualizar"/>';
			$html .= '<input type="hidden" name="id_ponto" id="id_ponto" value="'.$row["id_ponto"].'"/>';
			$html .= '<input type="hidden" name="simak" id="simak" value="'.$row["codigo_abrigo"].'"/>';
			$html .= '<input type="hidden" name="device" id="device" value="'. $_SERVER["REMOTE_ADDR"] .'"/>';
			$html .= '<input type="hidden" name="gmaps_latitude" id="gmaps_latitude" value="'.$row["gmaps_latitude"].'"/>';
			$html .= '<input type="hidden" name="gmaps_longitude" id="gmaps_longitude" value="'.$row["gmaps_longitude"].'"/>';
			$html .= 'Simak: <b>'.$row["codigo_abrigo"].'</b><br/><br/>';
			$html .= 'Endereço: <b>'.$row["endereco"].'</b><br/><br/><br/>';
			$html .= '<input type="button" onclick="atualizaGPS()" value="Atualizar GPS" style="height:70px;width:250px"/><br/>';
			$html .= '<input type="button" onclick="cancelar()" value="Cancelar" style="height:70px;width:250px"/>';
		}
	}
	if($acao == "atualizar"){
		$id_ponto = $_REQUEST["id_ponto"];
		$simak = $_REQUEST["simak"];
		$gmaps_latitude = $_REQUEST["gmaps_latitude"];
		$gmaps_longitude = $_REQUEST["gmaps_longitude"];
		$device = $_REQUEST["device"];

		$query = "insert into auditoria(ip_address,id_usuario,acao,obs ) values('". $device ."','0','4','Simak:". $simak ." Latitude:". $gmaps_latitude ." Longitude:". $gmaps_longitude ."');";
		$db->setQuery($query);
		$db->execute();
		
		$query = " update pontos set ";
		$query .= "gmaps_latitude = '". $gmaps_latitude ."', ";
		$query .= "gmaps_longitude = '". $gmaps_longitude ."' ";
		$query .= " where id_ponto = " . $id_ponto;
		$db->setQuery($query);
		$db->execute();

		$html .= 'Simak <b>'. $simak .'</b> atualizado com sucesso!<br/><br/>';
		$html .= '<input type="button" onclick="cancelar()" value="Atualizar Novo Simak" style="height:70px;width:250px"/>';
	}
}else{
	$html .= '<input type="hidden" name="acao" id="acao" value="busca"/>';
	$html .= '<b>Digite o Número Simak:</b><br/>';
	$html .= '<input type="tel" name="simak" id="simak" style="height:40px;width:250px"/><br/><br/>';
	$html .= '<input type="button" onclick="continua()" value="Procurar" style="height:70px;width:250px"/>';
}
echo $html;
?>
</form>
</center>
</body>
</html>