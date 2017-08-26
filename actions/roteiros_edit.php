<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_roteiro = $_REQUEST['id_roteiro'];
$query = 'select roteiros.*, gravidades.gmaps_latitude, gravidades.nome as gravidade, gravidades.gmaps_longitude from roteiros ';
$query .= ' left join gravidades on roteiros.id_gravidade = gravidades.id_gravidade ';
$query .= ' where id_roteiro = ' . $id_roteiro;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();
$nome_gravidade = $db_result->gravidade;
$latitude = $db_result->gmaps_latitude;
$longitude = $db_result->gmaps_longitude;
$cor = $db_result->cor;
$fonte = '000000';
if( hexdec($cor) <= 808080 ){
	$fonte = 'FFFFFF';
}

$drop = new Dropdown();
$gravidade = $drop->getHTMLFromQuery('select id_gravidade as code, nome as label from gravidades', $db_result->id_gravidade, true, 'id_gravidade', 'style="font-size:10pt; width:250px;"');

?>

<script type="text/javascript" src="javascript/roteiros.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jquery.colorPicker.js"/></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<script type="text/javascript">
$(function() {    
	$('#cor').colorPicker();
});
</script>

<link rel="stylesheet" href="styles/colorPicker.css" type="text/css" />

<form name="form" id="form" method="post">

	<input type="hidden" name="id_roteiro" id="id_roteiro" value="<?php echo $db_result->id_roteiro; ?>">

	<fieldset id="groups">
	
		<legend>Dados do Roteiro</legend>
		<div class="line">
			<table align="center" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td>
				<label style="width:60px">Nome:</label>
			</td>
			<td>
				<input type="text" name="nome" id="nome" class="medium_field"style="width:250px;" value="<?php echo $db_result->nome?>">
			</td>
			<td>
				<label style="width:90px">Referência:</label>
			</td>
			<td>
			<span><?php echo $gravidade;?></span>
			</td>
			<td>
				<label style="width:90px">Frequência:</label>
			</td>
			<td>
			<span>
				<select name="vistoria" id="frequencia" style="font-size:10pt; width:100px;">
					<option value=""></option>
					<option <?php if($db_result->frequencia == 'D'){echo 'selected';};?> value="D">Diária</option>
					<option <?php if($db_result->frequencia == 'M'){echo 'selected';};?> value="M">Mensal</option>
					<option <?php if($db_result->frequencia == 'T'){echo 'selected';};?> value="T">Trimestral</option>
				</select>
			</span>
			</td>
			<td>
				<label style="width:40px">Cor:</label>
			</td>
			<td>
				<input style="width:20px" id="cor" type="text" name="cor" value="#<?php echo $cor?>" />
			</td>
			</tr>
			</table>
		</div>
		<!--
		<div class="line">
			<label>Noturno:</label>
			<span class="field"><input type="checkbox" name="noturno" id="noturno" <?php echo ($db_result->noturno == 't')?' checked ':'';?> value="TRUE"></span>
			<label style="width:80px">Vistoria:</label>
			<span class="field"><input type="checkbox" name="vistoria" id="vistoria" <?php echo ($db_result->vistoria == 't')?' checked ':'';?> value="TRUE"></span>
			<label style="width:100px">Manutenção:</label>
			<span class="field"><input type="checkbox" name="manutencao" id="manutencao" <?php echo ($db_result->manutencao == 't')?' checked ':'';?> value="TRUE"></span>
			<label style="width:100px">Publicidade:</label>
			<span class="field"><input type="checkbox" name="publicidade" id="publicidade" <?php echo ($db_result->publicidade == 't')?' checked ':'';?> value="TRUE"></span>
			<label style="width:100px">Lavagem:</label>
			<span class="field"><input type="checkbox" name="lavagem" id="lavagem" <?php echo ($db_result->lavagem == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		-->
		<input type="hidden" name="noturno" id="noturno" value="<?php echo $db_result->noturno;?>">
		<input type="hidden" name="vistoria" id="vistoria" value="<?php echo $db_result->vistoria;?>">
		<input type="hidden" name="manutencao" id="manutencao" value="<?php echo $db_result->manutencao;?>">
		<input type="hidden" name="publicidade" id="publicidade" value="<?php echo $db_result->publicidade;?>">
		<input type="hidden" name="lavagem" id="lavagem" value="<?php echo $db_result->lavagem;?>">

		<?php

	$query = "";
// -23.589239,-46.815856 COTIA
$query .= " ";
$query .= " select *, ";
// ORDENAÇÃO TIPO 1
$query .= " sqrt( pow (". $latitude ." - cast(case coalesce(gmaps_latitude,'') when '' then '0' else gmaps_latitude end as double precision), 2) + pow (". $longitude ." - cast( case coalesce(gmaps_longitude,'') when '' then '0' else gmaps_longitude end as double precision), 2) ) as ordenacao, ";
// ORDENAÇÃO TIPO 2
//$query .= " ( ". $latitude ." - cast(gmaps_latitude as double precision) ) + ( ". $longitude ." - cast(gmaps_longitude as double precision) ) as ordenacao,";
$query .= "  pontos.id_ponto, endereco, pontosTipo.nome as tipo, pontosTipo.cor as tipo_cor, roteiros.cor as roteiro_cor, gmaps_latitude, gmaps_longitude,  pontos.id_roteiro, codigo_abrigo, posicao from pontos  ";
	$query .= " left join bairros on pontos.id_bairro = bairros.id_bairro ";
	$query .= " left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
	$query .= " left join pontosTipo on pontosTipo.id_tipo = pontosPadrao.id_tipo ";
	$query .= " left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
	$query .= " left join roteirosPontos on roteirosPontos.id_ponto = pontos.id_ponto  ";
	$query .= " where pontos.id_roteiro = ". $id_roteiro;
	$query .= " and coalesce(gmaps_latitude,'') != '' ";
	$query .= " and coalesce(gmaps_longitude,'') != '' ";
$query .= " order by  roteirosPontos.posicao,ordenacao desc  ";

//	$query .= " order by roteirosPontos.posicao, pontos.cep ";		
//	$query .= " order by roteirosPontos.posicao, pontos.gmaps_latitude desc, gmaps_longitude ";		

		//echo $query;

		$db = Database::getInstance();
		$db->setQuery($query);
		$db->execute();
		$db_pontos = $db->getResultSet();

		$total_pontos = $db->getRows();

		?>
		&nbsp;<br/> 
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <style>
  #sortable1 { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 270px;}
  #sortable1 li { margin: 5px; padding: 5px; font-size: 1em; width: 250px; }
  </style>
  <script>
  $(function() {
    $( "ul.droptrue" ).sortable({
      connectWith: "ul"
    });
 
    $( "ul.dropfalse" ).sortable({
      connectWith: "ul",
      dropOnEmpty: false
    });
	$( "#sortable1" ).sortable({
      cancel: ".ui-state-disabled"
    });
    $( "#sortable1" ).disableSelection();
  })
  </script>
  			<table align="center" border="0" cellspacing="0" cellpadding="0"><tr><td>
			<div align="right"><span>Total de Pontos de Parada: <b><?php echo $total_pontos;?></b></span></div>
			<div id="map-canvas" style="width: 680px; height: 400px;"></div>
			</td><td>
			<div align="right"><span>&nbsp;</span></div>
			<div style="width:300px; height:400px; overflow-y: scroll; overflow-x: hidden;">
<ul id="sortable1" class="droptrue">

</ul>
			</div>
			</td></tr>
			</table>

			<div align="left"><span>
	<?php
	$query  = ' ';
	$query .= ' select * from pontosTipo ';
	$query .= ' order by nome';

	$db = Database::getInstance();
	$db->setQuery($query);
	$db->execute();
	$db_result = $db->getResultSet();
	$contador = 0;
	echo '<table border="0" cellspacing="0" cellpadding="0"><tr>';
	if($db->getRows() > 0){
	foreach ($db_result as $row) {
		if($contador == 5){
			echo "</tr><tr>";
			$contador = 1;
		}else{
			$contador++;
		}

		$icon = "";
		$icon .= "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
		$icon .= $row["cor"];
		$icon .= "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";

		echo "<td width='130px'>";
		echo "<img src='";
		echo $icon;
		echo "'> " . $row["nome"];
		echo "</td>";
	}
	}
	echo "</tr></table>";
	?>
	</span>
			</div>

		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
			<input type="button" name="reset" id="reset" value="Limpar Ordenação">
			<!-- <input type="button" name="google" id="google" value="Ver no Google"> -->
			<input type="button" name="gera_vistoria" id="gera_vistoria" value="Gerar Vistoria">
		</div>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.13&sensor=false"></script>
		<script>

		var map;
		var infowindow = new google.maps.InfoWindow({ size: new google.maps.Size(150,50) });
		var directionsService = new google.maps.DirectionsService();
		var routeSegment = 0;
		var gmarkers = [];
		var contador = 0;
		var control = 0;
		var novo = [];
		var enderecos = [];
		var enderecos_tipo = [];

function calcula(valor){
	return  Math.sqrt( Math.pow(g.lat - parseFloat(valor.lat),2) + Math.pow(g.long - parseFloat(valor.long),2) );
}
function ordena(a, b){
	var base_a = calcula(a);
	var base_b = calcula(b);
	return (base_a - base_b)
}

var novo = [];
var g = {lat:parseFloat(<?php echo $latitude;?>), long:parseFloat(<?php echo $longitude;?>)};

function ordenaBagaca(bla){
	enderecos.sort(ordena)
	var ultimo = enderecos.shift();
	g.lat = parseFloat(ultimo.lat);
	g.long = parseFloat(ultimo.long);
	novo.push(ultimo);
	while(enderecos.length > 0){
		ordenaBagaca();
	}
	if(bla != undefined){
		novo.reverse();
		return novo;
	}
}
		function initialize() {
			errorMessage = '<div id="errorMessage"><div align="center">Carregando GPS.</div></div>';
			TINY.box.show({html:errorMessage,close:false,animate:true,width:480,height:100})

			geocoder = new google.maps.Geocoder();
			var myLatlng = new google.maps.LatLng(-23.552487154350533, -46.636356281475855);
			var mapOptions = {
				zoom: 12,
				center: myLatlng,
					mapTypeControl: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

			enderecos_tipo = [];
			enderecos = [
			<?php 
			$total_pontos = $db->getRows();
			if( $db_pontos && $total_pontos > 0 ){
				foreach ($db_pontos as $row) {
					echo chr(13);
					echo "{";
					echo "posicao:'".		$row["posicao"] ."', ";
					echo "ponto:'".		$row["id_ponto"] ."', ";
					echo "codigo:'".	$row["codigo_abrigo"] ."', ";
					echo "address:'".	$row["endereco"] ."',";
					echo "tipo_cor:'". $row["tipo_cor"] ."',";
					echo "tipo:'".		$row["tipo"] ."',";
					echo "roteiro_cor:'". $row["roteiro_cor"] ."',";
					echo "roteiro:".	$row["id_roteiro"] .", ";
					echo "lat:".		$row["gmaps_latitude"] .",";
					echo "long:".		$row["gmaps_longitude"] ."";
					echo "},";
				}
			}
			?>
			{}
			];
			enderecos.pop();
			if(enderecos[0].posicao == ''){
				enderecos = ordenaBagaca('ordena');
			}else{
				enderecos.reverse();			
			}
			plotaDados('start');
		}

		function plotaDados( tipo ){
			control++;
			routeSegment = control - 1;

			var waypts = [];
			start = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
			enderecos_tipo.push( enderecos.pop() );
			
			var ret = "";

			ret +=  '<li id="linha'+ routeSegment +'" ';
			fonte = '000000';

			ret +=  'id_ponto="'+ enderecos_tipo[ routeSegment ].ponto +'" ';
			ret +=  ' update="true" '
			ret +=  'class="ui-state-default"';
			cor = enderecos_tipo[ routeSegment ].roteiro_cor;
			ret +=  '>';
			ret +=  '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
			ret +=  '<img onclick="myclick(\''+ routeSegment +'\')" id="placa'+ routeSegment +'" src="';
			ret +=  'http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.3%7C45%7C'+ cor +'%7C12%7Cb%7C'+fonte+'%7C';
			ret +=  (routeSegment+1);
			ret +=  '&chl=%5Bv_square%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C';
			ret +=  '"/> ';
			ret +=  '</td><td width="100%">&nbsp;';
			ret +=  enderecos_tipo[ routeSegment ].address;
			ret += '<br/>&nbsp;<b>Tipo:</b> ' + enderecos_tipo[ routeSegment ].tipo;
			ret += '<br/>&nbsp;<b>Simak:</b> ' + enderecos_tipo[ routeSegment ].codigo;
			ret +=  '</td><td>';

			ret +=  '</td></tr></table>';
			ret +=  '</li>';

			gmarkers.push( 
				addMarker(
					start, 
					enderecos_tipo[routeSegment].address,
					enderecos_tipo[routeSegment].roteiro,
					enderecos_tipo[routeSegment].tipo_cor,
					enderecos_tipo[routeSegment].roteiro_cor,
					'FFFFFF'
				) 
			);	
	
			ret = ret.split(', República Federativa do Brasil').join('')
			ret = ret.split(', São Paulo').join('')
				
			document.getElementById('sortable1').innerHTML += ret;
			if(enderecos.length > 1){
				//plotaDados();
				// força tempo entre chamadas para não dar OVER_QUERY_LIMIT no google
				setTimeout(plotaDados, 5);
			}else{
				if(enderecos.length > 0){
					setTimeout( plotaDados('end') , 5);
				}
			}
			if(enderecos.length == 0){
				setTimeout( TINY.box.hide() , 5);
			}
		}

		function old_plotaDados( tipo ){
			control++;
			if(tipo == 'start'){
				start = new google.maps.LatLng(<?php echo $latitude;?>, <?php echo $longitude;?>);
				end = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				//end = enderecos[enderecos.length -1].address + ' Sao Paulo';
				var request = {
					avoidHighways: false,
					origin: start,
					destination: end,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
			}else if(tipo == 'end'){
				start = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				//start = enderecos[enderecos.length -1].address + ' Sao Paulo';
				end = new google.maps.LatLng(<?php echo $latitude;?>, <?php echo $longitude;?>);
				var request = {
					avoidHighways: false,
					origin: start,
					destination: end,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
				enderecos_tipo.push( enderecos.pop() );
				TINY.box.hide();
			}else{
				var waypts = [];
				start = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				enderecos_tipo.push( enderecos.pop() );
				for (var i = 0; i < 8; i++) {
					if(enderecos.length > 1){
						waypts.push({
						location: new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long),
						stopover:true
						});
						enderecos_tipo.push( enderecos.pop() );
					}
				}
				end = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				var request = {
					avoidHighways: false,
					origin: start,
					destination: end,
					waypoints: waypts,
					optimizeWaypoints: false,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
			}
			directionsService.route(request, function(response, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					eval('var directionsDisplay' + control +' =  new google.maps.DirectionsRenderer( { map: map, suppressMarkers:true});')
					eval('directionsDisplay' + control ).setDirections(response);
					var route = response.routes[0];
					var ret = "";
					for (var i = 0; i < route.legs.length; i++) {
						ret +=  '<li id="linha'+ routeSegment +'" ';
						if(routeSegment != 0 ){
							ret +=  'id_ponto="'+ enderecos_tipo[routeSegment - 1].ponto +'" ';
							ret +=  ' update="true" '
							ret +=  'class="ui-state-default"';
						}else{
							ret +=  ' update="false" '
							ret +=  'class="ui-state-default ui-state-disabled"';
						}
						ret +=  '>';
						ret +=  '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
						ret +=  '<img onclick="myclick(\''+ routeSegment +'\')" id="placa'+ routeSegment +'" src="';
						ret +=  'http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.3%7C45%7C<?php echo $cor?>%7C12%7Cb%7C<?php echo $fonte?>%7C';
						ret +=  routeSegment;
						ret +=  '&chl=%5Bv_square%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C';
						ret +=  '"/> ';
						ret +=  '</td><td width="100%">&nbsp;';
						if(routeSegment > 0){
							ret +=  enderecos_tipo[routeSegment - 1].address;
							ret += '<br/>&nbsp;<b>Tipo:</b> ' + enderecos_tipo[routeSegment - 1].tipo;
							ret += '<br/>&nbsp;<b>Simak:</b> ' + enderecos_tipo[routeSegment - 1].codigo;
						}else{
							//ret +=  route.legs[i].start_address;						
							ret += '<?php echo $nome_gravidade;?>'
						}
						//ret +=  '<br><b>Distância:</b> ' + route.legs[i].distance.text;
						ret +=  '</td><td>';


						ret +=  '</td></tr></table>';
						ret +=  '</li>';
						if(routeSegment > 0){
							gmarkers.push( 
								addMarker(
									route.legs[i].start_location, 
									enderecos_tipo[routeSegment - 1].address,
									enderecos_tipo[routeSegment - 1].roteiro,
									enderecos_tipo[routeSegment - 1].tipo_cor
								) 
							);							
						}else{
							gmarkers.push( 
								addMarker(
									route.legs[i].start_location, 
									route.legs[i].start_address
								)
							);
						}

						routeSegment += 1;
					}
					ret = ret.split(', República Federativa do Brasil').join('')
					ret = ret.split(', São Paulo').join('')
						
					document.getElementById('sortable1').innerHTML += ret;
					if(enderecos.length > 1){
						//plotaDados();
						// força tempo entre chamadas para não dar OVER_QUERY_LIMIT no google
						setTimeout(plotaDados,700);
					}else{
						if(enderecos.length > 0){
							setTimeout( plotaDados('end') , 700);
						}
					}
				}else{
					alert(status)
				}
			});
		}
		function myclick(i) {
			google.maps.event.trigger(gmarkers[i],"click");
		}
		function addMarker(pos, titulo, roteiro, cor){
			if(cor == undefined){
				cor = 'FFFFFF'
			}
			icon = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
			icon += cor;
			icon += "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";
			shadow = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.3%7C45%7C<?php echo $cor?>%7C12%7Cb%7C<?php echo $fonte?>%7C";
			shadow += (routeSegment + 1);
			shadow += "&chl=%5Bv_square%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";

			titulo = titulo.split(', República Federativa do Brasil').join('');
			titulo = titulo.split(', São Paulo').join('');
			var marker = new google.maps.Marker({
				position: pos, 
				map: map,  
				//shadow: iconShadow, 
				//icon: iconImage,
				icon: icon,
				shadow: shadow, 
				title: titulo
			});
			if(roteiro != undefined){
				google.maps.event.addListener(marker, 'click', function() {
					var end = enderecos_tipo[gmarkers.indexOf(marker)]
					var tit = '<b>Endereço:</b> ' + end.address
					tit += '<br/><b>Tipo:</b> ' + end.tipo
					tit += '<br/><b>Simak:</b> ' + end.codigo
					infowindow.setContent("&nbsp;<br/><a href='home.php?action=pontos_edit&id_ponto="+ end.ponto +"'>" + tit + "</a>"); 
					infowindow.open(map,marker);
				});
			}
			return marker;
		};
			window.onload = initialize;
		</script>
	</fieldset>

</form>