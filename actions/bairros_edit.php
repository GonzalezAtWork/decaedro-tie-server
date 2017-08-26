<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_bairro = $_REQUEST['id_bairro'];
$query = 'select * from bairros where id_bairro = ' . $id_bairro;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();


$drop = new Dropdown();
$select = $drop->getHTMLFromQuery('select id_zona as code, nome as label from zonas', $db_result->id_zona , false, 'id_zona', 'style="font-size:10pt; width:120px;"');


$query = "select id_ponto, endereco, gmaps_latitude, gmaps_longitude, id_tipo, codigo_abrigo from pontos ";
$query .= " left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
$query .= " where pontos.ativo = TRUE ";
// PARA TRAZER APENAS REGISTROS COM GEO-REFERENCIA
$query .= " and ( gmaps_latitude is not null and gmaps_latitude != '' ) ";
if( $id_bairro ){
	$query .= ' and id_bairro = ' . $id_bairro;	
}
$query .= ' order by gmaps_latitude desc, gmaps_longitude desc, endereco ';

////echo $query;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_pontos = $db->getResultSet();

$total_pontos = $db->getRows();

?>

<script type="text/javascript" src="javascript/bairros.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_bairro" id="id_bairro" value="<?php echo $db_result->id_bairro; ?>">

	<fieldset id="groups">
	
		<legend>Dados do Bairro</legend>
		<div class="line">
			<label style="width:60px">Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="small_field" value="<?php echo $db_result->nome?>"></span>
			<label style="width:50px">Zona:</label>
			<span><?php echo $select?></span>
			<label style="width:70px"><a title="Calcula distância a partir de Cotia" href="javascript:CalculaDistancia()">Distância</a>:</label>
			<span class="field"><input id="distancia" name="distancia" type="text" class="small_field" style="width:60px;" value="<?php echo $db_result->distancia?>"></span>
		</div>
		&nbsp;<br/>
		<div align="right"><span>Total de Pontos de Parada: <b><?php echo $total_pontos;?></b></span></div>
		<div id="map-canvas" style="width: 980px; height: 400px;"></div>
		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
		</div>

	</fieldset>
<script src="https://maps.googleapis.com/maps/api/js?v=3.13&sensor=false"></script>
		<script>
		var map;
		var infowindow = new google.maps.InfoWindow({ size: new google.maps.Size(150,50) });

		function codeBairro(address, lat, lng) {
			address += ", São Paulo - SP"
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
				} else {
					alert('Bairro não encontrado: ' + status);
				}
			});
		}
		function codeAddresses() {
			enderecos.pop();
			var ultimo = enderecos.length -1;
			if(ultimo >= 0){
				codeAddress(enderecos[ultimo].ponto, enderecos[ultimo].codigo, enderecos[ultimo].address, enderecos[ultimo].tipo, enderecos[ultimo].lat, enderecos[ultimo].long);
			}
		}
		function codeAddress(id_ponto, codigo, address, tipo, lat, lng) {
			icon = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
			icon += "FFFFFF";
			icon += "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";

			var marker;
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

		function CalculaDistancia(response, status){
			if (response == undefined){
				var service = new google.maps.DistanceMatrixService();
				service.getDistanceMatrix(
				{
					origins: ['Rua Nove de Julho, 52 - Cotia - São Paulo - SP'],
					destinations: ['<?php echo $db_result->nome?> -  São Paulo - SP'],
					travelMode: google.maps.TravelMode.DRIVING,
					unitSystem: google.maps.UnitSystem.METRIC,
					avoidHighways: false,
					avoidTolls: false
				}, CalculaDistancia);
			}else{
				if ( status != google.maps.DistanceMatrixStatus.OK ) {
					alert('Erro: ' + status);
				} else {
					var origins = response.originAddresses;
					var destinations = response.destinationAddresses;
					alert( 'Endereço: ' + destinations + '\nDistância: ' +response.rows[0].elements[0].distance.value )
				}
			}
		}

		function initialize() {
			geocoder = new google.maps.Geocoder();
			var mapOptions = {
				zoom: 13,
					mapTypeControl: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
			enderecos = [
			<?php 
			if($db_pontos && $total_pontos > 0){
				foreach ($db_pontos as $row) {
					echo chr(13);
					//	echo "codeAddress('". $row["id_ponto"] ."','". $row["endereco"] ."',". $row["gmaps_latitude"] .",". $row["gmaps_longitude"] .");";
					echo "{ponto:'". $row["id_ponto"] ."', codigo:'". $row["codigo_abrigo"] ."', address:'". $row["endereco"] ."',tipo:". $row["id_tipo"] .", lat:". $row["gmaps_latitude"] .",long:". $row["gmaps_longitude"] ."},";
				}
			}
			$total_pontos = $db->getRows();
			?>
			{}
			];
			codeAddresses();
			codeBairro('<?php echo $db_result->nome?>');
			// para salvar medições de distâncias
			// Automatiza();
		}
		initialize();


		function Automatiza(response, status){
			if (response == undefined){
				var service = new google.maps.DistanceMatrixService();
				service.getDistanceMatrix(
				{
					origins: ['Rua Nove de Julho, 52 - Cotia - São Paulo - SP'],
					destinations: ['<?php echo $db_result->nome?> -  São Paulo - SP'],
					travelMode: google.maps.TravelMode.DRIVING,
					unitSystem: google.maps.UnitSystem.METRIC,
					avoidHighways: false,
					avoidTolls: false
				}, Automatiza);
			}else{
				if ( status != google.maps.DistanceMatrixStatus.OK ) {
					alert('Erro: ' + status);
				} else {
					var origins = response.originAddresses;
					var destinations = response.destinationAddresses;
					//alert( 'Endereço: ' + destinations + '\nDistância: ' +response.rows[0].elements[0].distance.value )
					document.getElementById('distancia').value = response.rows[0].elements[0].distance.value;
					$.ajax({
						type: "POST",
						url: "ajax/bairros_update.php",
						data: {
							id_bairro:$('#id_bairro').val(),
							id_zona:$('#id_zona').val(),
							vistoria:$('#vistoria').val(),
							distancia:$('#distancia').val(),
							nome:$('#nome').val()
						},
						success: function(response) { if (!isJSON(response)) { return false };
							location.href="home.php?action=bairros_edit&id_bairro=<?php echo ($db_result->id_bairro + 1); ?>";
						}
					});
				}
			}
		}
		</script>
</form>