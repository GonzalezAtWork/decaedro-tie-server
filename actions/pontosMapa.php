<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_tipo = (isset($_REQUEST['tipo']))?$_REQUEST['tipo']:0;

$query = "select id_ponto, endereco, gmaps_latitude, gmaps_longitude, pontosPadrao.id_tipo, pontosTipo.cor, codigo_abrigo from pontos ";
$query .= " left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
$query .= " left join pontosTipo on pontosPadrao.id_tipo = pontosTipo.id_tipo ";
$query .= " where pontos.ativo = TRUE ";
// PARA TRAZER APENAS REGISTROS COM GEO-REFERENCIA
$query .= " and ( gmaps_latitude is not null and gmaps_latitude != '' ) ";
if( $id_tipo ){
	$query .= ' and pontosTipo.id_tipo = ' . $id_tipo;	
}
$query .= ' order by gmaps_latitude desc, gmaps_longitude desc, endereco ';

////echo $query;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultSet();

?>
<form name="form" id="form" method="post">

	<fieldset>
		<legend id="titulo">Mapa de Pontos de Parada por Tipo</legend>

		<script src="https://maps.googleapis.com/maps/api/js?v=3.13&sensor=false"></script>
		<script src="http://maps.googleapis.com/maps/api/js?libraries=drawing&sensor=true"></script>
		<script>
		var map;
		var infowindow = new google.maps.InfoWindow({ size: new google.maps.Size(150,50) });

		var enderecos;
		function codeAddresses() {
			enderecos.pop();
			var ultimo = enderecos.length -1;
			if(ultimo >= 0){
				codeAddress(enderecos[ultimo].ponto, enderecos[ultimo].codigo, enderecos[ultimo].address, enderecos[ultimo].tipo,enderecos[ultimo].tipo_cor, enderecos[ultimo].lat, enderecos[ultimo].long);
			}
		}
		function codeAddress(id_ponto, codigo, address, tipo, tipo_cor, lat, lng) {
			icon = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
			icon += tipo_cor;
			icon += "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";

			var marker;
			if(lat == undefined){
				address += " - São Paulo"
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						marker = new google.maps.Marker({
							id_ponto: id_ponto,
							map: map,		
							title:"<b>Simak:</b> " + codigo + "<br/><b>Endereço:</b> " + address,
							draggable:true,
							icon: icon, //'http://chart.apis.google.com/chart?chst=d_map_pin_letter_withshadow&chld=%7CFFFFFF',
							position: results[0].geometry.location
						});
						google.maps.event.addListener(marker, 'click', function() {
							new google.maps.InfoWindow({
								content: "&nbsp;<br/>&nbsp;<br/><a href='home.php?action=pontos_edit&id_ponto="+ marker.id_ponto +"'>" + marker.title + "</a>"
							}).open(map,marker);
						});
						// Continua procurando outros endereços
						codeAddresses();
					}else{
						console.log("Erro com GeoCode: " + status);
					}
				});
			}else{
				var latlng = new google.maps.LatLng(lat, lng);
				marker = new google.maps.Marker({
					id_ponto: id_ponto,
					map: map,		
					icon: icon, //'http://chart.apis.google.com/chart?chst=d_map_pin_letter_withshadow&chld=%7C52B552',
					title:"<b>Simak:</b> " + codigo + "<br/><b>Endereço:</b> " + address,
					position: latlng
				});
				google.maps.event.addListener(marker, "dragend", function(event) { 
					lat = event.latLng.lat(); 
					lng = event.latLng.lng(); 
					document.getElementById('gmaps_latitude').value = lat;
					document.getElementById('gmaps_longitude').value = lng;
				}); 			
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.setContent("&nbsp;<br/><a href='home.php?action=pontos_edit&id_ponto="+ marker.id_ponto +"'>" + marker.title + "</a>"); 
					infowindow.open(map,marker);
				});		
				// Continua adicionando marcadores
				codeAddresses();
			}
		}

		function initialize() {
			geocoder = new google.maps.Geocoder();
			var myLatlng = new google.maps.LatLng(-23.552487154350533, -46.636356281475855);
			var mapOptions = {
				zoom: 11,
				center: myLatlng,
				mapTypeControl: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

			var drawingManager = new google.maps.drawing.DrawingManager({  
				drawingControl: true,
				circleOptions: {
					editable: true
				},
				polylineOptions: {
					editable: true
				},
				polygonOptions: {
					editable: true
				},
				rectangleOptions: {
					editable: true
				}
			});
			drawingManager.setMap(map);

			enderecos = [
			<?php 
			if($db_result){
				foreach ($db_result as $row) {
					echo chr(13);
					if($row["gmaps_latitude"] == ""){
					//	echo "codeAddress('". $row["id_ponto"] ."','". $row["endereco"] ."');";
						echo "{ponto: '". $row["id_ponto"] ."', codigo:'". $row["codigo_abrigo"] ."', address:'". $row["endereco"] ."', tipo:1 },";
					}else{
					//	echo "codeAddress('". $row["id_ponto"] ."','". $row["endereco"] ."',". $row["gmaps_latitude"] .",". $row["gmaps_longitude"] .");";
						echo "{ponto:'". $row["id_ponto"] ."', codigo:'". $row["codigo_abrigo"] ."', address:'". $row["endereco"] ."',tipo:'". $row["id_tipo"] ."',tipo_cor:'". $row["cor"] ."', lat:". $row["gmaps_latitude"] .",long:". $row["gmaps_longitude"] ."},";
					}
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
		<div align="right"><span>Total de Pontos de Parada: <b><?php echo $total_pontos;?></b></span></div>
		<div id="map-canvas" style="width: 980px; height: 400px;"></div>
		<div align="left"><span>
<?php
$query = ' select * from pontosTipo ';
$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultSet();
$contador = 0;
echo "<table border=0 cellspacing=0 cellpadding=0><tr>";
echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
echo "<td width='300' rowspan='15'>&nbsp;</td>";
echo "<td rowspan='15' valign='middle' width='150' align='right'>";
echo "<a href='home.php?action=roteirosMapa'>Ver Todos Por Roteiro</a>";
echo "<br/>";
echo "<a href='home.php?action=pontosMapa'>Ver Todos Por Tipo</a>";
echo "</td>";
echo "</tr><tr>";
foreach ($db_result as $row) {
	if($contador == 5){
		echo "</tr><tr>";
		$contador = 1;
	}else{
		$contador++;
	}
	echo "<td width='130px'>";
	if($id_tipo == $row["id_tipo"] ){echo '<b>';}

	$icon = "";
	$icon .= "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
	$icon .= $row["cor"];
	$icon .= "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";

	echo "<img src='";
	echo $icon;
	echo "'> <a href='home.php?action=pontosMapa&tipo=" . $row["id_tipo"] . "'>" . $row["nome"] . "</a>";
	if($id_tipo == $row["id_tipo"] ){echo '</b>';}
	echo "</td>";
}
echo "</tr></table>";
?>
</span>
		</div>
	</fieldset>
</form>