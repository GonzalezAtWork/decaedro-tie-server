<?php

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', true);

$db = Database::getInstance();

$id_vistoria = (isset($_REQUEST['id_vistoria']))?$_REQUEST['id_vistoria']:0;

if ($id_vistoria != 0) {

	$query  = ' select vistorias.*, gravidades.gmaps_latitude, gravidades.nome as gravidade, gravidades.gmaps_longitude from vistorias ';
	$query .= ' left join gravidades on vistorias.id_gravidade = gravidades.id_gravidade ';
	$query .= ' where id_vistoria = ' . $id_vistoria;

	$db->setQuery($query);
	$db->execute();
	$db_result = $db->getResultAsObject();

	$nome_gravidade = $db_result->gravidade;
	$id_gravidade = $db_result->id_gravidade;
	$latitude = $db_result->gmaps_latitude;
	$longitude = $db_result->gmaps_longitude;

	$total = $db->getRows();
	$agendada = $db_result->agendada;
	$executada = $db_result->executada;
	$data = $db_result->data;
	$periodo = $db_result->periodo;

	if ($db_result->agendada == 't' || $db_result->executada == 't') {
		$disabled = " disabled ";
	} else {
		$disabled = "";
	}

	#Trazendo encarregado
	$query = "select id_equipe from vistoriasEquipes where id_vistoria = ".$id_vistoria;
	$db->setQuery($query);
	$db->execute();
	

	$db_result = $db->getResultSet();

	if ($db->getRows() > 0) {
		foreach ($db_result as $row) {
			$encarregado = $row['id_equipe'];
		}
	}else{
		$encarregado = 0;
	}

} else {

	$nome_gravidade = '';
	$latitude = '';
	$longitude = '';
	$id_gravidade = '';
	$disabled = "";
	$total = 0;
	$executada = "";
	$data = "";
	$periodo = "";
	$encarregado = "";

}


$drop = new Dropdown();
$gravidade = $drop->getHTMLFromQuery('select id_gravidade as code, nome as label from gravidades', $id_gravidade, true, 'id_gravidade', $disabled . ' style="font-size:10pt; width:250px;"');
$pop_id_tipo = $drop->getHTMLFromQuery('select id_tipo as code, nome as label from pontostipo', '', true, 'p_id_tipo', $disabled . ' style="font-size:10pt; width:250px;"');
$pop_id_roteiro = $drop->getHTMLFromQuery('select id_roteiro as code, nome as label from roteiros', '', true, 'p_id_roteiro', $disabled . ' style="font-size:10pt; width:250px;"');

?>

<style>
	.cell {
		float:left;
		width:50%;
	}
</style>

<script type="text/javascript" src="javascript/vistorias.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />

<?php
if ($disabled == '') {
	?>
	<script>
		$(function() {
			$( "#data" ).datepicker({
	      showOn: "button",
	      buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
	      buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
			});
		});
	</script>
<?php
}
?>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_vistoria" id="id_vistoria" value="<?php echo $id_vistoria;?>">

	<fieldset>

		<legend><?php echo (($total == 0) ? "Nova Vistoria" : "Visualização de Vistoria" )?></legend>

		<?php
		if ($total > 0) {
			?>

			<div class="line">
				<label>Código:</label>
				<span class="field"><b><?php echo substr('000000' . $id_vistoria, -5);?></b></span>
			</div>

			<?php

			$query  = " select roteiros.*, vistoriasRoteiros.id_vistoria, vistoriasRoteiros.qtd_pontos from roteiros ";
			$query .= " inner join vistoriasRoteiros on roteiros.id_roteiro = vistoriasRoteiros.id_roteiro and vistoriasRoteiros.id_vistoria = ". $id_vistoria;
			$query .= " order by id_roteiro; ";

			$db->setQuery($query);
			$db->execute();

			$result = $db->getResultSet();
			$label = "Roteiro:";
			if ($db->getRows() > 0) {
				foreach ($result as $row) {
					echo '<label>'.$label.'</label>';
					echo '<span class="field"><b>'.$row["nome"].'(<small>'.$row["qtd_pontos"].'pontos</small>)</b></span>';
					$label = "";
				}
			}
		}
		?>

		<div class="line">

			<div class="cell">
				<label>Data:</label>
				<span class="field"><input <?php echo $disabled;?> type="text" name="data" id="data" class="date_field" value="<?php echo $data;?>"></span>
			</div>

			<div class="cell">
				<label>Período:</label>
				<span class="field">
					<select name="periodo" id="periodo"<?php echo $disabled;?>>
						<option <?php echo (($periodo=='') ? 'selected' : '');?> value="">&nbsp;</option>
						<option <?php echo (($periodo=='D') ? 'selected' : '');?> value="D">Diurno</option>
						<option <?php echo (($periodo=='N') ? 'selected' : '');?> value="N">Noturno</option>
					</select>
				</span>
			</div>

		</div>

		<div class="line">

			<div class="cell">
				<label>Referência:</label>
				<span class="field"><?php echo $gravidade;?></span>
			</div>

			<div class="cell">
				<label>Encarregado:</label>
				<?php

				$dropQuery  = "select u.id_usuario as code, u.nome as label from usuarios u order by 2;";
				$encarregados = $drop->getHTMLFromQuery($dropQuery, $encarregado, true, 'id_equipe', $disabled . ' style="font-size:10pt; width:250px;"');
				echo $encarregados;
				?>
			</div>
		</div>
<?php

if( $id_vistoria != 0 ){
	$query  = " select pontos.id_ponto, endereco, pontosTipo.nome as tipo, pontosTipo.cor as tipo_cor, roteiros.cor as roteiro_cor, gmaps_latitude, gmaps_longitude,  pontos.id_roteiro, codigo_abrigo, posicao, pontosTipo.id_tipo from pontos  ";
	$query .= " left join bairros on pontos.id_bairro = bairros.id_bairro ";
	$query .= " left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
	$query .= " left join pontosTipo on pontosTipo.id_tipo = pontosPadrao.id_tipo ";
	$query .= " left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
	$query .= " inner join ocorrencias on ocorrencias.id_ponto = pontos.id_ponto  ";
	$query .= " where ocorrencias.id_vistoria = ". $id_vistoria;
	$query .= " order by ocorrencias.posicao  ";

	$db = Database::getInstance();
	$db->setQuery($query);
	$db->execute();
	$db_result = $db->getResultSet();

	?>
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

		function initialize() {
			//Mensagem
			errorMessage = '<div id="errorMessage"><div align="center">Carregando GPS.</div></div>';
			TINY.box.show({html:errorMessage,close:false,animate:true,width:480,height:100});


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
			if( $db_result && $total_pontos > 0 ){
				foreach ($db_result as $row) {
					echo chr(13);
					echo "{";
					echo "posicao:'".	   $row["posicao"] ."', ";
					echo "ponto:'".		$row["id_ponto"] ."', ";
					echo "codigo:'".	   $row["codigo_abrigo"] ."', ";
					echo "address:'".	   $row["endereco"] ."',";
					echo "tipo_cor:'".   $row["tipo_cor"] ."',";
					echo "id_tipo:'".		$row["id_tipo"] ."',";
					echo "tipo:'".       $row["tipo"] ."',";
					echo "roteiro_cor:'".$row["roteiro_cor"] ."',";
					echo "roteiro:".     $row["id_roteiro"] .", ";
					echo "lat:".         $row["gmaps_latitude"] .",";
					echo "long:".        $row["gmaps_longitude"] ."";
					echo "},";
				}
			}
			?>
			{}
			];
			enderecos.pop();
			enderecos.reverse();
			plotaDados('start');
		}

		
		function plotaDados( tipo ){
			control++;
			if(tipo == 'start'){
				start = new google.maps.LatLng(<?php echo $latitude;?>, <?php echo $longitude;?>);
				if(enderecos.length > 0){
					end = new google.maps.LatLng(enderecos[enderecos.length -1].lat, enderecos[enderecos.length -1].long);
				}else{
					end = 'Sao Paulo';
				}
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
						fonte = 'FFFFFF';
						if(routeSegment != 0 ){
							ret +=  'id_ponto="'+ enderecos_tipo[routeSegment - 1].ponto +'" ';
							<?php if($disabled == ''){ ?>
								ret +=  ' update="true" '
								ret +=  'class="ui-state-default"';
							<?php }else{ ?>							
								ret +=  ' update="false" '
								ret +=  'class="ui-state-default ui-state-disabled"';
							<?php }; ?>
							cor = enderecos_tipo[routeSegment - 1].roteiro_cor;
						}else{
							cor = 'CCCCCC';
							ret +=  ' update="false" '
							ret +=  'class="ui-state-default ui-state-disabled"';
						}
						ret +=  '>';
						ret +=  '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
						ret +=  '<img onclick="myclick(\''+ routeSegment +'\')" id="placa'+ routeSegment +'" src="';
						ret +=  'http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.3%7C45%7C'+ cor +'%7C12%7Cb%7C'+fonte+'%7C';
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
						<?php if($agendada != 't' && $executada != 't'){ ?>
						if(routeSegment != 0){
							ret +=  '<img id="icone'+ routeSegment +'" onclick="mudaCor('+ routeSegment +')" width="15" height="15" src="http://png.findicons.com/files/icons/573/must_have/48/delete.png"/>';
						}
						<?php } ?>
						ret +=  '</td></tr></table>';
						ret +=  '</li>';
						if(routeSegment > 0){
							gmarkers.push( 
								addMarker(
									route.legs[i].start_location, 
									enderecos_tipo[routeSegment - 1].address,
									enderecos_tipo[routeSegment - 1].roteiro,
									enderecos_tipo[routeSegment - 1].tipo_cor,
									enderecos_tipo[routeSegment - 1].roteiro_cor,
									'FFFFFF'
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
						setTimeout(plotaDados,1500);
					}else{
						if(enderecos.length > 0){
							setTimeout( plotaDados('end') , 1500);
						}
					}
					if(enderecos.length == 0){
						setTimeout( TINY.box.hide() , 1500);
					}
				}else{
					alert(status);
					setTimeout(plotaDados,1500);
				}
			});
		}
		function myclick(i) {
			google.maps.event.trigger(gmarkers[i],"click");
		}
		function addMarker(pos, titulo, roteiro, cor, rot_cor, rot_fonte){
			if(cor == undefined){
				cor = 'FFFFFF'
			}
			if(rot_cor == undefined){
				rot_cor = 'CCCCCC';
				rot_fonte = 'ffffff';
			}
			icon = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.13%7C0%7C";
			icon += cor;
			icon += "%7C15%7Cb%7C00FFFF%7C&chl=%5Bv_disk%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C";
			shadow = "http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.3%7C45%7C"+rot_cor+"%7C12%7Cb%7C"+rot_fonte+"%7C";
			shadow += routeSegment;
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
					var end = enderecos_tipo[gmarkers.indexOf(marker) - 1]
					var tit = '<b>Endereço:</b> ' + end.address
					tit += '<br/><b>Tipo:</b> ' + end.tipo
					tit += '<br/><b>Simak:</b> ' + end.codigo
					// não deixa abrir edição de Ponto a partir da vistoria
					//infowindow.setContent("&nbsp;<br/><a href='home.php?action=pontos_edit&id_ponto="+ end.ponto +"'>" + tit + "</a>"); 
					infowindow.setContent("&nbsp;<br/>" + tit + ""); 
					infowindow.open(map,marker);
				});
			}
			return marker;
		};
			window.onload = initialize;
			</script>
			<br/>&nbsp;<br/>
			
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
  });
  function mudaCor(posicao, force){
		if( document.getElementById('linha' + posicao).style.background == '' || force == 'OK' ){
			document.getElementById('linha' + posicao).style.background = '#A0A0A0';
			icone = "";
			icone +=  'http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.3%7C45%7C'+ 'CCCCCC' +'%7C12%7Cb%7C'+ '666666' +'%7C';
			icone +=  posicao;
			icone +=  '&chl=%5Bv_square%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C';

			document.getElementById('placa' + posicao).src = icone;
			document.getElementById('icone' + posicao).src = 'http://png.findicons.com/files/icons/977/rrze/48/left_grey.png';
			gmarkers[posicao].setShadow(icone);
			document.getElementById('linha' + posicao).setAttribute('update','false');
		}else{
			cor = enderecos_tipo[posicao].roteiro_cor
			icone = "";
			icone +=  'http://chart.apis.google.com/chart?cht=d&chdp=public&chld=0.3%7C45%7C'+ cor +'%7C12%7Cb%7C'+ 'FFFFFF' +'%7C';
			icone +=  posicao;
			icone +=  '&chl=%5Bv_square%270r%5C0r%5C1r%5Cvi%27%5C2r%5CfC%5C%5B6..%27r%5C%5Dha%5CV%5C%5B=%273r%5C%5Dsc%27%5Cf%5C4r%5Cf%5C5r%5CtC%5Cva%5C%5Do%5Cba%5C';

			document.getElementById('linha' + posicao).style.background = '';		
			document.getElementById('placa' + posicao).src = icone;
			document.getElementById('icone' + posicao).src = 'http://png.findicons.com/files/icons/573/must_have/48/delete.png';
			gmarkers[posicao].setShadow(icone);
			document.getElementById('linha' + posicao).setAttribute('update','true');
		}
  }
	function tiraTipo(id_tipo) {
		for (t = 0; t < enderecos_tipo.length ;t++) {
			if (enderecos_tipo[t].id_tipo == id_tipo) {
				mudaCor(t+1,'OK');
			}
		}
	}
  </script>
			<table border="0" cellspacing="0" cellpadding="0"><tr><td>
			<div align="right"><span>Total de Pontos de Parada: <b><?php echo $total_pontos;?></b></span></div>
			<div id="map-canvas" style="width: 680px; height: 400px;"></div>
			</td><td>
			<div align="center">
				<span class="field"><a href="javascript:checa_roteiro()">Inserir Roteiro</a> | SIMAK: <input type="text" name="simak" id="simak" class="medium_small" style="width:120px" value=""> <img onclick="checa_simak()" width="18" height="18" src="http://png.findicons.com/files/icons/2226/matte_basic/32/search.png" title="Checar Simak"/></span>
			</div>
			<div style="width:300px; height:400px; overflow-y: scroll; overflow-x: hidden;">
<ul id="sortable1" class="droptrue">
<?php
/*
	if($db_result){
		foreach ($db_result as $row) {
				echo '<li id="linha'. $row["posicao"] .'" class="ui-state-default">';
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
				echo '<img id="placa'. $row["posicao"] .'" width="20" height="20" src="http://gebweb.net/optimap/iconsnew/red'. $row["posicao"] .'.png"/> ';
				echo '</td><td width="100%">&nbsp;';
				echo $row["endereco"];
				echo '</td><td><img id="icone'. $row["posicao"] .'" onclick="mudaCor('. $row["posicao"] .')" width="15" height="15" src="http://png.findicons.com/files/icons/573/must_have/48/delete.png"/>';
				echo '</td></tr></table>';
				echo '</li>';
				
		}
	}
*/
?>
</ul>
			</div>
			</td></tr>
			</table>
			<div align="left" width="100%" style="background-color:#FFFFFF;"><span>
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
		echo " <a href='javascript:tiraTipo(". $row["id_tipo"] .")'><img width='10' height='10' src='http://png.findicons.com/files/icons/573/must_have/48/delete.png'></a>";
		echo "</td>";
	}
	}
	echo "</tr></table>";
	?>
	</span>
			</div>
	<?php 
}
?>
		<div align="center" style="padding-top:10px;">
		<?php if($total == 0){ ?>
				<span><input type="button" name="insert" id="insert" value="Salvar"></span>
		<?php }else{ ?>
			<?php if($agendada != 't' && $executada != 't'){ ?>
					<span><input type="button" name="save" id="save" value="Salvar"></span>
					<span><input type="button" name="delete" id="delete" value="Cancelar"></span>
					<span><input type="button" name="schedule" id="schedule" value="Agendar"></span>
			<?php }; ?>
			<?php if($agendada == 't' && $executada != 't'){ ?>
					<span><input type="button" name="cria_guia" id="cria_guia" value="Emitir Guia de Vistoria"></span>
					<span><input type="button" name="exec" id="exec" value="Executar"></span>
			<?php } ?>
			<?php if( $executada == 't'){ ?>
					<span><input type="button" name="exec" id="exec" value="Visualizar Dados"></span>
			<?php } ?>
		<?php } ?>
		</div>

	</fieldset>

</form>
<div style="display:none">
<?php echo $pop_id_tipo;?>
<?php echo $pop_id_roteiro;?>
</div>