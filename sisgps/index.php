<?php 
$checkBrowser = strrpos($_SERVER["HTTP_USER_AGENT"], "Kalitera SisGPS");
if ($checkBrowser === false){
	die();
}
header("Access-Control-Allow-Origin: *");
function anti_sql_injection($str) {
	if (!is_numeric($str)) {
		$str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
		//$str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
	}
	return $str;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
		
	<head>
		<title>Ajuste de Posição GPS</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	</head>
	<script language="javascript">
		function cancelar(){
			Android.loading_show();
			location.href="http://sisgps.kalitera.com.br";
		}
		function atualizaGPS(){			
			var str_dados = "";
			if(typeof(Android) != "undefined"){
				str_dados = Android.js_getGPS(document.getElementById("simak").value, document.getElementById("gmaps_latitude").value, document.getElementById("gmaps_longitude").value);
			}else{
				str_dados = '{';
				str_dados += '"hora":"'+  +'",';
				str_dados += '"simak":"'+ document.getElementById("simak").value +'",';
				str_dados += '"old_latitude":"'+ document.getElementById("gmaps_latitude").value +'",';
				str_dados += '"old_longitude":"'+ document.getElementById("gmaps_longitude").value +'",';
				str_dados += '"latitude":"'+ document.getElementById("gmaps_latitude").value +'",';
				str_dados += '"longitude":"'+ document.getElementById("gmaps_longitude").value +'",';
				str_dados += '"altitude":"'+ "" +'",';
				str_dados += '"accuracy":"'+ "" +'",';
				str_dados += '"velocidade":"'+ "" +'",';
				str_dados += '"bearing":"'+ "" +'",';
				str_dados += '"device":"'+ document.getElementById("device").value +'",';
				str_dados += '"token":"'+ "" +'"';
				str_dados += '}';
			}
			if(str_dados != 'Testando: NULO'){
				//alert( str_dados );
				var dados = eval( '(' + str_dados + ')' );
				document.getElementById("hora").value =				dados.hora;
				document.getElementById("simak").value =			dados.simak;
				document.getElementById("gmaps_latitude").value =	dados.latitude;
				document.getElementById("gmaps_longitude").value =	dados.longitude;
				document.getElementById("old_latitude").value =		dados.old_latitude;
				document.getElementById("old_longitude").value =	dados.old_longitude;
				document.getElementById("altitude").value =			dados.altitude;
				document.getElementById("accuracy").value =			dados.accuracy;
				document.getElementById("velocidade").value =		dados.velocidade;
				document.getElementById("bearing").value =			dados.bearing;
				if(dados.device != "" && dados.device != "null"){
					document.getElementById("device").value =			dados.device;
				}
				document.getElementById("token").value =			dados.token;
				continua();
			}else{
				alert('Erro com sinal GPS!');
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
<form name="form1" id="form1" action="http://sisgps.kalitera.com.br" method="post">
<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$html = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	include('../classes/XMLObject.php');
	include('../classes/database.php');
	#Conexão ao banco de dados
	$db = Database::getInstance();

	$acao = (isset($_REQUEST['acao']))?anti_sql_injection($_REQUEST['acao']):"";
	$id_ponto = (isset($_REQUEST['id_ponto']))?anti_sql_injection($_REQUEST['id_ponto']):"";
	$simak = (isset($_REQUEST['simak']))?anti_sql_injection($_REQUEST['simak']):"";
	$gmaps_latitude = (isset($_REQUEST['gmaps_latitude']))?anti_sql_injection($_REQUEST['gmaps_latitude']):"";
	$gmaps_longitude = (isset($_REQUEST['gmaps_longitude']))?anti_sql_injection($_REQUEST['gmaps_longitude']):"";
	$old_latitude = (isset($_REQUEST['old_latitude']))?anti_sql_injection($_REQUEST['old_latitude']):"";
	$old_longitude = (isset($_REQUEST['old_longitude']))?anti_sql_injection($_REQUEST['old_longitude']):"";
	$altitude = (isset($_REQUEST['altitude']))?anti_sql_injection($_REQUEST['altitude']):"";
	$accuracy = (isset($_REQUEST['accuracy']))?anti_sql_injection($_REQUEST['accuracy']):"";
	$velocidade = (isset($_REQUEST['velocidade']))?anti_sql_injection($_REQUEST['velocidade']):"";
	$bearing = (isset($_REQUEST['bearing']))?anti_sql_injection($_REQUEST['bearing']):"";
	$device = (isset($_REQUEST['device']))?anti_sql_injection($_REQUEST['device']):"";
	$token = (isset($_REQUEST['token']))?anti_sql_injection($_REQUEST['token']):"";
	$hora = (isset($_REQUEST['hora']))?anti_sql_injection($_REQUEST['hora']):"";
	if($device == ""){
		$device = $_SERVER["REMOTE_ADDR"];
	}

	if($acao == "busca"){
		$query = "select * from pontos where codigo_abrigo = '" . $simak . "' or codigo_novo ilike '%". $simak ."%'";
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
			$html .= '<input type="hidden" name="gmaps_latitude" id="gmaps_latitude" value="'.$row["gmaps_latitude"].'"/>';
			$html .= '<input type="hidden" name="gmaps_longitude" id="gmaps_longitude" value="'.$row["gmaps_longitude"].'"/>';
			$html .= '<input type="hidden" name="old_latitude" id="old_latitude" value=""/>';
			$html .= '<input type="hidden" name="old_longitude" id="old_longitude" value=""/>';
			$html .= '<input type="hidden" name="altitude" id="altitude" value=""/>';
			$html .= '<input type="hidden" name="accuracy" id="accuracy" value=""/>';
			$html .= '<input type="hidden" name="velocidade" id="velocidade" value=""/>';
			$html .= '<input type="hidden" name="bearing" id="bearing" value=""/>';
			$html .= '<input type="hidden" name="device" id="device" value="'. $device .'"/>';
			$html .= '<input type="hidden" name="token" id="token" value=""/>';
			$html .= '<input type="hidden" name="hora" id="hora" value=""/>';
			$html .= 'Simak: <b>'.$row["codigo_abrigo"].'</b><br/><br/>';
			$html .= 'Otima: <b>'.$row["codigo_novo"].'</b><br/><br/>';
			$html .= 'Endereço: <b>'.$row["endereco"].'</b><br/><br/><br/>';
			$html .= '<input type="button" onclick="atualizaGPS()" value="Atualizar GPS" style="height:70px;width:250px"/><br/>';
			$html .= '<input type="button" onclick="cancelar()" value="Cancelar" style="height:70px;width:250px"/>';
		}
	}
	if($acao == "atualizar"){
		$query  = "";

		$query .= "insert into pontosGPS( ";
		$query .= " id_ponto, simak, gmaps_latitude, gmaps_longitude, old_latitude, old_longitude, altitude, accuracy, velocidade, bearing, device, hora, token ";
		$query .= ") values(";
		$query .= " ". $id_ponto ." ,";
		$query .= "'". $simak ."',";
		$query .= "'". $gmaps_latitude ."',";
		$query .= "'". $gmaps_longitude ."',";
		$query .= "'". $old_latitude ."',";
		$query .= "'". $old_longitude ."',";
		$query .= "'". $altitude ."',";
		$query .= "'". $accuracy ."',";
		$query .= "'". $velocidade ."',";
		$query .= "'". $bearing ."',";
		$query .= "'". $device ."',";
		$query .= "'". $hora ."',";
		$query .= "'". $token ."'";
		$query .= ");";

		if($token != ""){
			$query .= "insert into auditoria(ip_address,id_usuario,acao,obs ) values(";
			$query .= "'". $device ."',";
			$query .= "( select id_usuario from mobile_login where token = '". $token ."' ),";
			$query .= "'4',";
			$query .= "'Simak:". $simak ." Latitude:". $gmaps_latitude ." Longitude:". $gmaps_longitude ."'";
			$query .= ");";
		}else{
			$query .= "insert into auditoria(ip_address,id_usuario,acao,obs ) values(";
			$query .= "'". $device ."',";
			$query .= "'0',"; // Usuário Sistema
			$query .= "'4',";
			$query .= "'Simak:". $simak ." Latitude:". $gmaps_latitude ." Longitude:". $gmaps_longitude ."'";
			$query .= ");";
		}
		
		$query .= " update pontos set ";
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