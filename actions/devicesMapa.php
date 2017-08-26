<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$device = (isset($_REQUEST['device']))?$_REQUEST['device']:"";

if( $device == "" ){
	$query  = "";
	$query .= " select data,to_char(data,'dd/MM/yyyy HH24:MI') as data_formatada,  device, usuarios.id_usuario, usuarios.nome, latitude, longitude from gps_logger  ";
	$query .= "		inner join usuarios on gps_logger.id_usuario = usuarios.id_usuario ";
	$query .= " where id_logger in ( ";
	$query .= " 	select max(id_logger) from gps_logger group by device ";
	$query .= " ) ";
	$query .= " order by data desc ";
}else{
	$query  = "";
	$query .= " select data,to_char(data,'dd/MM/yyyy HH24:MI') as data_formatada,  device, usuarios.id_usuario, usuarios.nome, latitude, longitude from gps_logger  ";
	$query .= "		inner join usuarios on gps_logger.id_usuario = usuarios.id_usuario ";
	$query .= " where device = '". $device ."'";
	$query .= " order by data desc ";
}
$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultSet();

if($device == ""){
	$colors = array(
		"FF0000","00FF00","0000FF","FFFF00","00FFFF","FF00FF","C0C0C0",
		"000000","000033","000066","000099","0000CC",
		"003300","003333","003366","003399","0033CC","0033FF",
		"006600","006633","006666","006699","0066CC","0066FF",
		"009900","009933","009966","009999","0099CC","0099FF",
		"00CC00","00CC33","00CC66","00CC99","00CCCC","00CCFF",
		"00FF33","00FF66","00FF99","00FFCC",
		"330000","330033","330066","330099","3300CC","3300FF",
		"333300","333333","333366","333399","3333CC","3333FF",
		"336600","336633","336666","336699","3366CC","3366FF",
		"339900","339933","339966","339999","3399CC","3399FF",
		"33CC00","33CC33","33CC66","33CC99","33CCCC","33CCFF",
		"33FF00","33FF33","33FF66","33FF99","33FFCC","33FFFF",
		"660000","660033","660066","660099","6600CC","6600FF",
		"663300","663333","663366","663399","6633CC","6633FF",
		"666600","666633","666666","666699","6666CC","6666FF",
		"669900","669933","669966","669999","6699CC","6699FF",
		"66CC00","66CC33","66CC66","66CC99","66CCCC","66CCFF",
		"66FF00","66FF33","66FF66","66FF99","66FFCC","66FFFF",
		"990000","990033","990066","990099","9900CC","9900FF",
		"993300","993333","993366","993399","9933CC","9933FF",
		"996600","996633","996666","996699","9966CC","9966FF",
		"999900","999933","999966","999999","9999CC","9999FF",
		"99CC00","99CC33","99CC66","99CC99","99CCCC","99CCFF",
		"99FF00","99FF33","99FF66","99FF99","99FFCC","99FFFF",
		"CC0000","CC0033","CC0066","CC0099","CC00CC","CC00FF",
		"CC3300","CC3333","CC3366","CC3399","CC33CC","CC33FF",
		"CC6600","CC6633","CC6666","CC6699","CC66CC","CC66FF",
		"CC9900","CC9933","CC9966","CC9999","CC99CC","CC99FF",
		"CCCC00","CCCC33","CCCC66","CCCC99","CCCCCC","CCCCFF",
		"CCFF00","CCFF33","CCFF66","CCFF99","CCFFCC","CCFFFF",
		"FF0000","FF0033","FF0066","FF0099","FF00CC","FF00FF",
		"FF3300","FF3333","FF3366","FF3399","FF33CC","FF33FF",
		"FF6600","FF6633","FF6666","FF6699","FF66CC","FF66FF",
		"FF9900","FF9933","FF9966","FF9999","FF99CC","FF99FF",
		"FFCC00","FFCC33","FFCC66","FFCC99","FFCCCC","FFCCFF",
		"FFFF00","FFFF33","FFFF66","FFFF99","FFFFCC","FFFFFF"
	);
}else{
	$colors = array("FF0000","0000FF");
}
?>
<form name="form" id="form" method="post">

	<fieldset>
		<legend id="titulo">Rastreio de Aparelhos Celulares</legend>

		<script src="https://maps.googleapis.com/maps/api/js?v=3.13&sensor=false"></script>
		<script>
		var map;
		var infowindow = new google.maps.InfoWindow({ size: new google.maps.Size(150,50) });

		var enderecos;
		function codeAddresses() {
			enderecos.pop();
			var ultimo = enderecos.length -1;
			if(ultimo >= 0){
				codeAddress(
					enderecos[ultimo].id_usuario,
					enderecos[ultimo].data, 
					enderecos[ultimo].device, 
					enderecos[ultimo].nome, 
					enderecos[ultimo].cor, 
					enderecos[ultimo].lat, 
					enderecos[ultimo].lng
				);
			}
		}
		function codeAddress(id_usuario, data, device, nome, cor, lat, lng) {
			icon = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
			icon += cor;
			icon += "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";
			var marker;
			var latlng = new google.maps.LatLng(lat, lng);
			marker = new google.maps.Marker({
				device: device,
				map: map,		
				icon: icon,
				link: "<b>DATA:</b> " + data + "<br/><b>IMEI:</b> " + device + "<br/><b>USUÁRIO:</b> " + nome,
				title: nome,
				position: latlng
			});
			google.maps.event.addListener(marker, "dragend", function(event) { 
				lat = event.latLng.lat(); 
				lng = event.latLng.lng(); 
				document.getElementById('gmaps_latitude').value = lat;
				document.getElementById('gmaps_longitude').value = lng;
			}); 			
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.setContent("&nbsp;<br/><a href='home.php?action=devicesMapa&device="+ marker.device +"'>" + marker.link + "</a>"); 
				infowindow.open(map,marker);
			});		
			gmarkers.push(marker);
			codeAddresses();
		}

		function initialize() {
			geocoder = new google.maps.Geocoder();
			var myLatlng = new google.maps.LatLng(-23.552487154350533, -46.636356281475855);
			var mapOptions = {
				zoom: 11,
					mapTypeControl: false,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
			enderecos = [
			<?php 
			if($db_result){
				$contador = 0;
				foreach ($db_result as $row) {
					echo chr(13);
					echo "{";
					echo "	id_usuario:'".	$row["id_usuario"] ."', ";
					echo "	data:'".		$row["data_formatada"] ."', ";
					echo "	device:'".		$row["device"] ."', ";
					echo "	nome:'".		$row["nome"] ."', ";
					if($device == ""){
						echo "	cor:'".			$colors[$contador] ."', ";
					}else{
						if($contador == 0){
							echo "	cor:'".			$colors[0] ."', ";
						}else{
							echo "	cor:'".			$colors[1] ."', ";						
						}
					}
					echo "	lat:".			$row["latitude"] .", ";
					echo "	lng:".			$row["longitude"] ." ";
					echo "},";
					$contador++;
				}
			}
			$total_pontos = $db->getRows();
			?>
			{}
			];
			codeAddresses();
		}
		window.onload = initialize;
		</script>
		<div align="right"><span>Total de Aparelhos: <b><?php echo $total_pontos;?></b></span></div>
		<div id="map-canvas" style="width: 980px; height: 400px;"></div>
		<div>
			<?php 
			if($db_result){
				$contador = 0;
				$total = $db->getRows() - 1;
				if($device == ""){
					echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";
					$bla = 0;
					foreach ($db_result as $row) {
						if($bla == 0){
							echo "<tr>";
						}
						echo "<td width='200'>";
						$icon = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
						$icon .= $colors[$contador];
						$icon .= "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";
						echo "<a href='javascript:myclick(". ($total - $contador) .")' title='". $row["data_formatada"] ." - ".$row["device"]."'>";
						echo "<img src='". $icon."'/>&nbsp;";
						echo $row["nome"];
						echo "</a>";
						echo "</td>";
						if($bla == 4){
							$bla = 0;
							echo "</tr>";
						}else{
							$bla++;
						}
						$contador++;
					}
					echo "</table>";
				}else{
					$retorno = "";
					foreach ($db_result as $row) {
						if($retorno == ""){
							$retorno = "asdf";
							$icon = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
							$icon .= $colors[0];
							$icon .= "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";
							echo "<a href='javascript:myclick(0)'>";
							echo "<img src='". $icon."'/>&nbsp;";
							echo "Posição Atual - " . $row["device"]. " - ". $row["nome"];
							echo "</a>";
							echo "<br/>";
							$icon = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
							$icon .= $colors[1];
							$icon .= "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";
							echo "<a href='#'><img src='". $icon."'/>&nbsp;";
							echo "Posições Anteriores</a>";
						}
					}	
				}
			}
			$total_pontos = $db->getRows();
			?>		
		</div>
		<script language="javascript">
		var gmarkers = [];
		function myclick(i) {
			map.setZoom(16);
			map.panTo(gmarkers[i].getPosition());
			google.maps.event.trigger(gmarkers[i],"click");
		}
		</script>
	</fieldset>
</form>
