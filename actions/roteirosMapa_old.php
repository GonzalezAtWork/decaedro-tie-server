<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_tipo = (isset($_REQUEST['tipo']))?$_REQUEST['tipo']:0;

$id_roteiro = (isset($_REQUEST['id_roteiro']))?$_REQUEST['id_roteiro']:0;

$CEP = (isset($_REQUEST['CEP']))?$_REQUEST['CEP']:0;

$CEP = str_replace('-','',$CEP);

$LIMITE = (isset($_REQUEST['LIMITE']))?$_REQUEST['LIMITE']:0;
$OFFSET = (isset($_REQUEST['OFFSET']))?$_REQUEST['OFFSET']:0;

$query = "select cast(replace(cep,'-','') as integer) as bla, id_ponto, endereco, gmaps_distancia, gmaps_latitude, gmaps_longitude, id_tipo, codigo_abrigo from pontos ";
$query .= " left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
$query .= " where pontos.ativo = TRUE ";
// PARA TRAZER APENAS REGISTROS COM GEO-REFERENCIA
$query .= " and ( gmaps_latitude is not null and gmaps_latitude != '' ) ";
if( $id_tipo ){
	$query .= ' and id_tipo = ' . $id_tipo;	
}
if($CEP != 0){
	$CEP_INICIAL = intval( substr( $CEP, 0, -4 ) . '0000' );
	$CEP_FINAL = intval( substr( $CEP, 0, -4 ) . '9000' );
//	$query .= " and cast(replace(cep,'-','') as integer) > ". ($CEP - 20000) ." and cast(replace(cep,'-','') as integer) < ". ($CEP + 20000);
//	$query .= " and cast(replace(cep,'-','') as integer) > ". $CEP_INICIAL ." and cast(replace(cep,'-','') as integer) < ". $CEP_FINAL;

$query .= " and codigo_abrigo in( ";
$query .= " '1364','231','1761','1476','1477','2096','2100','2102','2106','655','2074','736','744','745','1873','1620','788','1784','793','794','2182','1777','1431','1472','1351','1347','1348','856','853','1865','2033','2034','1764','877','1785','2028','1780','897','1773','1425','1550' ";
$query .= " ) ";

}else{
	$query .= ' and id_bairro in ( select id_bairro from bairrosRoteiro where id_roteiro = '. $id_roteiro .') ';
}
//$query .= ' and gmaps_distancia = 0 ';
//$query .= ' order by gmaps_latitude, gmaps_longitude, endereco LIMIT 20 ';
$query .= ' order by gmaps_distancia ';
if($LIMITE != 0){
	$query .= '  LIMIT ' . $LIMITE ;
	$query .= '  OFFSET ' . $OFFSET ;
}


//echo $query;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultSet();

?>
<form name="form" id="form" method="post">

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
				center: myLatlng,
					mapTypeControl: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
			directionsDisplay1 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay2 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay3 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay4 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay5 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay6 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay7 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay8 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay9 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay10 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay11 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay12 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay13 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay14 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay15 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay16 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay17 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay18 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay19 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay20 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay21 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay22 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay23 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay24 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay25 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay26 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay27 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay28 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});
			directionsDisplay29 =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});

			enderecos = [
			<?php 
			if($db_result){
				foreach ($db_result as $row) {
					echo chr(13);
					if($row["gmaps_latitude"] == ""){
					//	echo "codeAddress('". $row["id_ponto"] ."','". $row["endereco"] ."');";
						echo "{distancia:". $row["gmaps_distancia"] .", ponto: '". $row["id_ponto"] ."', codigo:'". $row["codigo_abrigo"] ."', address:'". $row["endereco"] ."', tipo:1 },";
					}else{
					//	echo "codeAddress('". $row["id_ponto"] ."','". $row["endereco"] ."',". $row["gmaps_latitude"] .",". $row["gmaps_longitude"] .");";
						echo "{distancia:". $row["gmaps_distancia"] .", ponto:'". $row["id_ponto"] ."', codigo:'". $row["codigo_abrigo"] ."', address:'". $row["endereco"] ."',tipo:". $row["id_tipo"] .", lat:". $row["gmaps_latitude"] .",long:". $row["gmaps_longitude"] ."},";
					}
				}
			}
			$total_pontos = $db->getRows();
			?>
			{}
			];
			enderecos.pop();
			enderecos.reverse();
			calculateDistances();
			//plotaDados('start');
		}
		var control = 0;
		function addMarker(pos,titulo){
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
				start = 'Rua nove de julhos, 52 Cotia Sao Paulo';
				end = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				//end = enderecos[enderecos.length -1].address + ' Sao Paulo';
				var request = {
					origin: start,
					destination: end,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
			}else if(tipo == 'end'){
				start = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				//start = enderecos[enderecos.length -1].address + ' Sao Paulo';
				
				end = 'Rua nove de julhos, 52 Cotia Sao Paulo';
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
						//location: enderecos[enderecos.length -1].address + ' Sao Paulo',
						stopover:true
						});
						enderecos.pop();
					}
				}
				//start = 'rua nove de julho, 52 cotia';
				//end = 'rua nove de julho, 50 cotia';
				
				end = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				//end = enderecos[enderecos.length -1].address + ' Sao Paulo';

				// comentado para manter a ligação entre as rotas
				// enderecos.pop();
				var request = {
					avoidHighways: false,
					origin: start,
					destination: end,
					waypoints: waypts,
					optimizeWaypoints:true,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
			}
			directionsService.route(request, function(response, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					eval('directionsDisplay' + control ).setDirections(response);
					var route = response.routes[0];
					var ret = "";
					for (var i = 0; i < route.legs.length; i++) {
						//ret += '<b>Trecho: ' + routeSegment + '</b><br><b>de</b> ';
						ret += '<a href="javascript:myclick(\''+ routeSegment +'\')">';
						ret += "<img src='http://gebweb.net/optimap/iconsnew/red"+ routeSegment +".png'/>"
						ret += "</a><br><b>De</b> ";
						ret += '<a href="javascript:myclick(\''+ routeSegment +'\')">'+ route.legs[i].start_address + '</a><br><b>Até</b> ';
						ret += route.legs[i].end_address + '<br><b>Distância</b> ';
						ret += route.legs[i].distance.text + '<br><br>';
						gmarkers.push( addMarker(route.legs[i].start_location, route.legs[i].start_address ) );
						routeSegment += 1;
					}
					ret = ret.split(', República Federativa do Brasil').join('')
					ret = ret.split(', São Paulo').join('')
						
					document.getElementById('texto').innerHTML += ret;
					if(enderecos.length > 1){
						plotaDados();
					}else{
						if(enderecos.length > 0){
							plotaDados('end');					
						}
					}
				}else{
					alert(status)
				}
			});
		}
		var routeSegment = 0;
		window.onload = initialize;
		contador = 0;
		var novo = [];
		function calculateDistances(origin1) {
			plotaDados('start');
			}
		function pcalculateDistances(origin1) {
			if(origin1 == undefined){
				//origin1 = 'centro Sao Paulo';
				origin1 = 'Rua Nove de Julho, 52 - Cotia - Sao Paulo';
			}
			destinationA = [];
			for ( a = 0; a < enderecos.length;a++ ) {
				//destinationA.push( enderecos[a].address + ' Sao Paulo');
				destinationA.push( new google.maps.LatLng(enderecos[a].lat, enderecos[a].long) );
			}
			var service = new google.maps.DistanceMatrixService();
			service.getDistanceMatrix(
			{
				origins: [origin1],
				destinations: destinationA,
				travelMode: google.maps.TravelMode.DRIVING,
				unitSystem: google.maps.UnitSystem.METRIC,
				avoidHighways: false,
				avoidTolls: false
			}, callback);
		}
		function callback(response, status) {
			if (status != google.maps.DistanceMatrixStatus.OK) {
				alert('Error was: ' + status);
			} else {
				var origins = response.originAddresses;
				var destinations = response.destinationAddresses;
				for (var i = 0; i < origins.length; i++) {
					var results = response.rows[i].elements;
					for (var j = 0; j < results.length; j++) {
						//alert(enderecos[j].address +' = '+ destinations[j] + ' : ' + results[j].distance.value)
						if(results[j].distance == undefined){
							//alert('eita')
						}else{
							enderecos[j].distancia = results[j].distance.value;
						}
					}
				}
				enderecos.sort( function (a, b){
					return a.distancia - b.distancia
				});
				enderecos.reverse();
			}
			bla = ""
			for ( var a = 0; a < enderecos.length;a++ ) {
				bla = 'update pontos set gmaps_distancia = ' + enderecos[a].distancia + ' where id_ponto = ' +enderecos[a].ponto  +';\n' + bla;
			}
			alert(bla)
			plotaDados('start');
		}
		</script>
		<div align="right"><span>Total de Pontos de Parada: <b><?php echo $total_pontos;?></b></span></div>
		<div id="map-canvas" style="width: 980px; height: 400px;"></div>
		<br/>&nbsp;
		<div id="texto"></div>
		<br/>&nbsp;
	</fieldset>
</form>