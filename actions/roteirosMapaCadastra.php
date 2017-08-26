<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_tipo = (isset($_REQUEST['tipo']))?$_REQUEST['tipo']:0;

$id_roteiro = (isset($_REQUEST['id_roteiro']))?$_REQUEST['id_roteiro']:0;

$texto = (isset($_REQUEST['texto']))?$_REQUEST['texto']:'';

if($texto != ''){
	$db = Database::getInstance();
	$db->setQuery($texto);
	$db->execute();
}

$query = "select id_ponto, endereco, gmaps_latitude, gmaps_longitude, id_tipo, codigo_abrigo from pontos ";
$query .= " left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
$query .= " where pontos.ativo = TRUE ";
// PARA TRAZER APENAS REGISTROS COM GEO-REFERENCIA
$query .= " and ( gmaps_latitude is not null and gmaps_latitude != '' ) ";
if( $id_tipo ){
	$query .= ' and id_tipo = ' . $id_tipo;	
}

	$query .= " and gmaps_endereco = '' ";
	$query .= " and gmaps_latitude != '-10.000000000000000' and gmaps_longitude != '-10.000000000000000' ";

//$query .= ' and id_bairro in ( select id_bairro from bairrosRoteiro where id_roteiro = '. $id_roteiro .') ';
$query .= ' order by gmaps_latitude, gmaps_longitude, endereco LIMIT 1 ';

//$query .= " and CEP != 'NOT FOUND' and CEP != 'WEB ERROR' and CEP != 'SQL ERROR' and CEP != '' ";
//$query .= ' order by CEP desc LIMIT 100 ';


//echo $query;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultSet();

?>
<form name="form" id="form" action="home.php?action=roteirosMapaCadastra&id_roteiro=<?php echo $id_roteiro;?>" method="post">
	<fieldset>
		<legend id="titulo">Roteiro de Pontos de Parada</legend>

		<script src="https://maps.googleapis.com/maps/api/js?v=3.13&sensor=false"></script>
		<script>
		var map;
		var enderecos;
		var directionDisplay;
		var directionsService = new google.maps.DirectionsService();

var iconImage = new google.maps.MarkerImage("http://www.geocodezip.com/mapIcons/marker_green.png",
      // This marker is 20 pixels wide by 34 pixels tall.
      new google.maps.Size(20, 34),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is at 6,20.
      new google.maps.Point(9, 34));
	  
	  
  var iconShadow = new google.maps.MarkerImage('http://www.google.com/mapfiles/shadow50.png',
      // The shadow image is larger in the horizontal dimension
      // while the position and offset are the same as for the main image.
      new google.maps.Size(37, 34),
      new google.maps.Point(0,0),
      new google.maps.Point(9, 34));
      // Shapes define the clickable region of the icon.
      // The type defines an HTML &lt;area&gt; element 'poly' which
      // traces out a polygon as a series of X,Y points. The final
      // coordinate closes the poly by connecting to the first
      // coordinate.
  var iconShape = {
      coord: [9,0,6,1,4,2,2,4,0,8,0,12,1,14,2,16,5,19,7,23,8,26,9,30,9,34,11,34,11,30,12,26,13,24,14,21,16,18,18,16,20,12,20,8,18,4,16,2,15,1,13,0],
      type: 'poly',
	  strokeColor: '#FF0000'
  };
var infowindow = new google.maps.InfoWindow(
  { 
    size: new google.maps.Size(150,50)
  });

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
			directionsDisplay =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});

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
						echo "{ponto:'". $row["id_ponto"] ."', codigo:'". $row["codigo_abrigo"] ."', address:'". $row["endereco"] ."',tipo:". $row["id_tipo"] .", lat:". $row["gmaps_latitude"] .",long:". $row["gmaps_longitude"] ."},";
					}
				}
			}
			$total_pontos = $db->getRows();
			?>
			{}
			];
			enderecos.pop();
			plotaDados('start');
		}
		var control = 0;
		function addMarker(pos,titulo){
			if(titulo == undefined){
			titulo = '';
			}
			titulo = titulo.split(', República Federativa do Brasil').join('');
			titulo = titulo.split(', São Paulo').join('');
			var marker = new google.maps.Marker({
				position: pos, 
				map: map,  
				//shadow: iconShadow, 
				//icon: iconImage,
				icon: 'http://gebweb.net/optimap/iconsnew/red'+ routeSegment +'.png',
				title: titulo
			});
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.setContent("&nbsp;<br/>" + marker.title); 
				infowindow.open(map,marker);
			});
			return marker;
		};
		var gmarkers = [];
		function myclick(i) {
			google.maps.event.trigger(gmarkers[i],"click");
			window.location.href="#";
		}
		function plotaDados( tipo ){
			control++;
			if(tipo == 'start'){
//				start = 'Rua Nove de Julho, 52 Cotia Sao Paulo';
				start = new google.maps.LatLng('-23.58992', '-46.81578000000002');
				end = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				var request = {
					origin: start,
					destination: end,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
			}else if(tipo == 'end'){
				start = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
//				end = 'Rua Nove de Julho, 52 Cotia Sao Paulo';
				end = new google.maps.LatLng('-23.58992', '-46.81578000000002');
				var request = {
					origin: start,
					destination: end,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
				enderecos.pop();
			}else{
				var waypts = [];
				start = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				enderecos.pop();
				for (var i = 0; i < 8; i++) {
					if(enderecos.length > 1){
						//alert(enderecos[i].address +': '+ enderecos[i].lat +','+ enderecos[i].long)
						waypts.push({
						location: new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long),
						stopover:true
						});
						enderecos.pop();
					}
				}
				//start = 'rua nove de julho, 52 cotia';
				//end = 'rua nove de julho, 50 cotia';
				end = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				// comentado para manter a ligação entre as rotas
				// enderecos.pop();
				var request = {
					origin: start,
					destination: end,
					waypoints: waypts,
					optimizeWaypoints:true,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
			}
			directionsService.route(request, function(response, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					eval('directionsDisplay').setDirections(response);
					var route = response.routes[0];
					var ret = "";
					for (var i = 0; i < route.legs.length; i++) {
						if( route.legs[i].start_address != undefined && route.legs[i].start_address.indexOf('Nove de Julho, 52') <0 ){ 
							ret += "update pontos set gmaps_endereco = '";
							//alert(route.legs[i].start_location)
							ret += route.legs[i].start_address.split("'").join("´");;
							ret += "' where id_ponto = '<?php echo $row["id_ponto"]; ?>'";
							//ret += "' where gmaps_latitude = '" + route.legs[i].start_location.lat() + "'";
							//ret += " and gmaps_longitude = '" + route.legs[i].start_location.lng() + "'";
							ret += ";\n";
						}
						gmarkers.push( addMarker(route.legs[i].start_location, route.legs[i].start_address ) );
						routeSegment += 1;
					}
					//ret = ret.split(', República Federativa do Brasil').join('')
					//ret = ret.split(', São Paulo').join('')
					//ret = ret.split('Avenida').join('Av.')
					//ret = ret.split('Rua').join('R.')
						
					document.getElementById('texto').value += ret;
					if(enderecos.length > 1){
						plotaDados();
					}else{
						if(enderecos.length > 0){
							plotaDados('end');					
						}
					}
					if(ret != ""){
						//document.forms[0].Submit();
						document.forms[0].submit();
					}
				}else{
					alert(status)
				}
			});
		}
		var routeSegment = 0;
		window.onload = initialize;
		</script>
		<div align="right"><span>Total de Pontos de Parada: <b><?php echo $total_pontos;?></b></span></div>
		<div id="map-canvas" style="width: 980px; height: 400px;"></div>
		<br/>&nbsp;

			<textarea wrap=off rows=5 cols=80 id="texto" name="texto"></textarea>
			<input type="submit"/>

		<br/>&nbsp;
	</fieldset>
</form>