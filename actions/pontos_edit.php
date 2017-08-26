<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_ponto = $_REQUEST['id_ponto'];
$query = 'select pontos.*, s.status_nome, roteirosPontos.posicao as posicao_roteiro from pontos ';
$query .= " left join roteirosPontos on pontos.id_ponto = roteirosPontos.id_ponto ";
$query .= " inner join ( ";
$query .= " 	select A.id_ponto, C.nome as status_nome from pontosStatusHistorico A ";
$query .= " 	inner join ( ";
$query .= " 		SELECT 	id_ponto , max(data) as data ";
$query .= " 		FROM pontosStatusHistorico ";
$query .= '			WHERE id_ponto = ' . $id_ponto;
$query .= " 		GROUP BY id_ponto ";
$query .= " 	) B ON B.id_ponto = a.id_ponto and b.data = a.data ";
$query .= " 	inner join pontosStatus C on A.id_status = C.id_status ";
$query .= " ) s on pontos.id_ponto = s.id_ponto  ";
$query .= 'where pontos.id_ponto = ' . $id_ponto;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();


$drop = new Dropdown();

$padrao = $drop->getHTMLFromQuery('select id_padrao as code, nome as label from pontosPadrao', $db_result->id_padrao, true, 'id_padrao', 'style="font-size:10pt; width:150px;"');
$roteiro = $drop->getHTMLFromQuery('select id_roteiro as code, nome as label from roteiros', $db_result->id_roteiro, true, 'id_roteiro', 'style="font-size:10pt; width:150px;"');
$regional = $drop->getHTMLFromQuery('select id_regional as code, nome as label from regionais', $db_result->id_regional, true, 'id_regional', 'style="font-size:10pt; width:150px;"');
$bairro = $drop->getHTMLFromQuery('select id_bairro as code, nome as label from bairros', $db_result->id_bairro, true, 'id_bairro', 'style="font-size:10pt; width:350px;"');
$limiteterreno = $drop->getHTMLFromQuery('select id_limite_terreno as code, nome as label from limiteterreno', $db_result->id_limite_terreno, true, 'id_limite_terreno', 'style="font-size:10pt; width:150px;"');
$pisocalcada = $drop->getHTMLFromQuery('select id_piso_calcada as code, nome as label from pisoCalcada', $db_result->id_piso_calcada, true, 'id_piso_calcada', 'style="font-size:10pt; width:150px;"');

$inclinacao = $drop->getHTMLFromQuery('select id_inclinacao as code, nome as label from inclinacoes', $db_result->id_inclinacao, true, 'id_inclinacao', 'style="font-size:10pt; width:150px;"');

?>

<script type="text/javascript" src="javascript/pontos.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
  <script>
  $(function() {
    $( "#dt_implantacao" ).datepicker({
      showOn: "button",
      buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
      buttonImageOnly: true,
	  dateFormat: "yy-mm-dd"
    });
    $( "#dt_painel_calcada" ).datepicker({
      showOn: "button",
      buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
      buttonImageOnly: true,
	  dateFormat: "yy-mm-dd"
    });
  });
  </script>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<form name="form" id="form" method="post">

	<input type="hidden" name="id_ponto" id="id_ponto" value="<?php echo $db_result->id_ponto; ?>">

	<fieldset>
		<legend id="titulo">Dados do Ponto de Parada</legend>
	<div id="tela1" titulo="Dados do Ponto de Parada">
		
		<div class="line">
			<label style="width:200px">Status:</label>
			<span class="field"><a href="home.php?action=pontosStatusHistorico&id_ponto=<?php echo $db_result->id_ponto; ?>"><?php echo $db_result->status_nome?></a></span>
		</div>
		<div class="line">
			<label style="width:200px">Simak:</label>
			<span class="field">
				<input type="text" name="codigo_abrigo" id="codigo_abrigo" class="small_field" style="width:80px" value="<?php echo $db_result->codigo_abrigo?>">
			</span>
		</div>
		<div class="line">
			<label style="width:200px">Nº Ótima:</label>
			<span class="field">
				<input type="text" name="codigo_novo" id="codigo_novo" class="small_field" style="width:120px" value="<?php echo $db_result->codigo_novo?>">
			</span>
		</div>
		<div class="line">
			<label style="width:200px">Endereço:</label>
			<span class="field"><input type="text" name="endereco" id="endereco" class="medium_field" value="<?php echo $db_result->endereco?>"></span>
		</div>
		<div class="line">
			<label style="width:200px">CEP:</label>
			<span class="field"><input type="text" name="cep" id="cep" class="small_field" style="width:80px" value="<?php echo $db_result->cep?>"></span>
		</div>
		<div class="line">
			<label style="width:200px">Padrão:</label>
			<span><?php echo $padrao?></span>
		</div>
		<div class="line">
			<label style="width:200px">Posição Global:</label>
			<span class="field"><input type="text" name="posicao_global" id="posicao_global" class="small_field" style="width:80px" value="<?php echo $db_result->posicao_global?>"></span>
		</div>
		<div class="line">
			<label style="width:200px">Roteiro:</label>
			<span><?php echo $roteiro?></span>
			<label style="width:70px">Posição:</label>
			<span><input type="text" name="posicao_roteiro" id="posicao_roteiro" class="small_field" style="width:40px;" value="<?php echo $db_result->posicao_roteiro?>"></span>
		</div>
		<div class="line">
			<label style="width:200px">Noturno:</label>
			<span class="field"><input type="checkbox" name="noturno" id="noturno" <?php echo ($db_result->noturno == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label style="width:200px">Adm. Regional:</label>
			<span><?php echo $regional?></span>
		</div>
		<div class="line">
			<label style="width:200px">Bairro:</label>
			<span><?php echo $bairro?></span>
		</div>

		<div class="line">
			<label style="width:200px">Data de Implantação Abrigo:</label>
			<span class="field"><input type="text" name="dt_implantacao" id="dt_implantacao" class="small_field" style="width:100px;" value="<?php echo $db_result->dt_implantacao?>"></span>
		</div>
		<div class="line">
			<label style="width:200px">Data de Implantação Painel:</label>
			<span class="field">
			<input type="checkbox" name="painel_calcada" id="painel_calcada" <?php echo ($db_result->painel_calcada == 't')?' checked ':'';?> value="TRUE">
			<input type="text" name="dt_painel_calcada" id="dt_painel_calcada" class="small_field" style="width:100px;" value="<?php echo $db_result->dt_painel_calcada?>">
			</span>
		</div>
		<div class="line">
			<label style="width:200px">Conjugados:</label>
			<span class="field"><input type="text" name="conjugados" id="conjugados" class="medium_field" value="<?php echo $db_result->conjugados?>"></span>
		</div>
		<div style="padding-top:10px;">
			<label style="width:200px">Observações:</label>
			<span class="field"><textarea name="observacoes" id="observacoes" rows="3" cols="35"><?php echo $db_result->observacoes?></textarea></span>
		</div>

	</div>
	<div id="tela2" style="display:none" titulo="Dados do Passeio (calçada)">
		<div class="line">
			<label style="width:200px">Inclinação:</label>
			<span><?php echo $inclinacao?></span>
		</div>
		
		<div class="line">
			<label style="width:200px">Limite de Terreno:</label>
			<span class="field">
				<span><?php echo $limiteterreno?></span>
				<input type="text" name="limite_terreno_obs" id="limite_terreno_obs" class="small_field" value="<?php echo $db_result->limite_terreno_obs?>">
			</span>
		</div>
		
		<div class="line">
			<label style="width:200px">Piso da Calçada:</label>
			<span class="field">
				<span><?php echo $pisocalcada?></span>
				<input type="text" name="piso_calcada_obs" id="piso_calcada_obs" class="small_field" value="<?php echo $db_result->piso_calcada_obs?>">
			</span>
		</div>

		<div class="line">
			<label style="width:200px">Poste:</label>
			<span class="field">
			<input type="checkbox" name="poste" id="poste" <?php echo ($db_result->poste == 't')?' checked ':'';?> value="TRUE">
			<input type="text" name="poste_quantos" id="poste_quantos" class="small_field" value="<?php echo $db_result->poste_quantos?>">
			</span>
		</div>
		<div class="line">
			<label style="width:200px">Elétrica:</label>
			<span class="field"><input type="checkbox" name="eletrica" id="eletrica" <?php echo ($db_result->eletrica == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label style="width:200px">Secundário:</label>
			<span class="field"><input type="checkbox" name="secundario" id="secundario" <?php echo ($db_result->secundario == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label style="width:200px">Iluminação Pública:</label>
			<span class="field"><input type="checkbox" name="iluminacao_publica" id="iluminacao_publica" <?php echo ($db_result->iluminacao_publica == 't')?' checked ':'';?> value="TRUE"></span>
		</div>

		<div class="line">
			<label style="width:200px">Largura da Calçada:</label>
			<span class="field"><input type="text" name="largura_calcada" id="largura_calcada" class="small_field" value="<?php echo $db_result->largura_calcada?>"></span>
		</div>
		<div class="line">
			<label style="width:200px">Distância da Calçada:</label>
			<span class="field"><input type="text" name="distancia_calcada" id="distancia_calcada" class="small_field" value="<?php echo $db_result->distancia_calcada?>"></span>
		</div>
	</div>
	<div id="tela3" style="display:none" titulo="Interferências">
	<table width="100%" border="0">
	<tr>
		<td align="center"><h3><b>LADO ESQUERDO</b></h3></td>
		<td align="center"><h3><b>LADO DIREITO</b></h3></td>
	</tr>
	<tr>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td>
		<?php
		$html = "";

		#Conecta na base de dados
		$db = Database::getInstance();

		$query  = " select i.nome, i.id_interferencia, pi.tipo, pi.id_ponto, pi.metragem   ";
		$query .= " from interferencias i ";
		$query .= " left join pontosInterferencias pi  ";
		$query .= "		on pi.id_interferencia = i.id_interferencia ";
		$query .= "		and pi.tipo = 'E' " ;
		$query .= "		and pi.id_ponto = ". $db_result->id_ponto ;
		$query .= " order by i.nome; ";

		////echo $query;

		$db->setQuery($query);
		$db->execute();

		$result = $db->getResultSet();
		foreach ($result as $row) {
			$tipo = "E";
			?>
				<div class="line">
					<label style="width:150px"><?php echo $row["nome"];?>:</label>
					<span class="field">
					<input type="hidden" name="interferencia_<?php echo $tipo;?>_codigo[]" id="interferencia_<?php echo $tipo;?>_codigo[]" value="<?php echo $row["id_interferencia"];?>">
					<input type="text" name="interferencia_<?php echo $tipo;?>[]" id="interferencia_<?php echo $tipo;?>[]" class="small_field" style="width:40px" value="<?php echo $row["metragem"];?>"> m
					</span>
				</div>
			<?php
		}
		?>
		</td>
		<td>
		<?php
		$html = "";

		#Conecta na base de dados
		$db = Database::getInstance();

		$query  = " select i.nome, i.id_interferencia, pi.tipo, pi.id_ponto, pi.metragem   ";
		$query .= " from interferencias i ";
		$query .= " left join pontosInterferencias pi  ";
		$query .= "		on pi.id_interferencia = i.id_interferencia ";
		$query .= "		and pi.tipo = 'D' " ;
		$query .= "		and pi.id_ponto = ". $db_result->id_ponto ;
		$query .= " order by i.nome; ";

		////echo $query;

		$db->setQuery($query);
		$db->execute();

		$result = $db->getResultSet();
		foreach ($result as $row) {
			$tipo = "D";
			?>
				<div class="line">
					<label style="width:150px"><?php echo $row["nome"];?>:</label>
					<span class="field">
					<input type="hidden" name="interferencia_<?php echo $tipo;?>_codigo[]" id="interferencia_<?php echo $tipo;?>_codigo[]" value="<?php echo $row["id_interferencia"];?>">
					<input type="text" name="interferencia_<?php echo $tipo;?>[]" id="interferencia_<?php echo $tipo;?>[]" class="small_field" style="width:40px" value="<?php echo $row["metragem"];?>"> m
					</span>
				</div>
			<?php
		}
		?>
		</td>
	</tr>
	</table>
	</div>


	<div id="tela4" style="display:none" titulo="Croquis" align="center">
		<img name="bmp_croquis" id="bmp_croquis" src="<?php echo ($db_result->croquis == '')?'images/embranco.jpg':'data:image/jpeg;base64,'.$db_result->croquis; ?>" width="700" height="500"/>
		<textarea style="display:none"  name="croquis" id="croquis"><?php echo $db_result->croquis?></textarea><br/>&nbsp;<br/>
		<input type="file" name="file_croquis" id="file_croquis"/>
	</div>


	<div id="tela5" style="display:none" titulo="Google Maps" align="center">
<div class="line">
		Latitude: <input type="text" name="gmaps_latitude" id="gmaps_latitude" value="<?php echo $db_result->gmaps_latitude; ?>">
		Longitude: <input type="text" name="gmaps_longitude" id="gmaps_longitude" value="<?php echo $db_result->gmaps_longitude; ?>">
</div>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.13&sensor=false"></script>
		<script>
		var map;
		function codeAddress(address, lat, lng) {
			if(address != ""){
				address += " - São Paulo"
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
						var marker = new google.maps.Marker({
							map: map,		
							draggable:true,
							position: results[0].geometry.location
						});
						google.maps.event.addListener(marker, "dragend", function(event) { 
							lat = event.latLng.lat(); 
							lng = event.latLng.lng(); 
							document.getElementById('gmaps_latitude').value = lat;
							document.getElementById('gmaps_longitude').value = lng;
						}); 
					} else {
						alert('Endereço não encontrado: ' + status);
					}
				});
			}else{
				var latlng = new google.maps.LatLng(lat, lng);
				map.setCenter(latlng);
				var marker = new google.maps.Marker({
					map: map,		
					draggable:true,
					position: latlng
				});
				google.maps.event.addListener(marker, "dragend", function(event) { 
					lat = event.latLng.lat(); 
					lng = event.latLng.lng(); 
					document.getElementById('gmaps_latitude').value = lat;
					document.getElementById('gmaps_longitude').value = lng;
				}); 			
			}
		}
		function initialize() {
			
        geocoder = new google.maps.Geocoder();
			var mapOptions = {
				zoom: 16,
					mapTypeControl: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
<?php if($db_result->gmaps_latitude == ""){ ?>
			codeAddress('<?php echo $db_result->endereco;?>');
<?php }else{ ?>
			codeAddress('',<?php echo $db_result->gmaps_latitude?>, <?php echo $db_result->gmaps_longitude?>);
<?php } ?>
		}
		</script>
		<div id="map-canvas" style="width: 980px; height: 400px;"></div>
	</div>
		<div align="center" style="padding-top:10px;">
		<!-- Abas retiradas devido reunião -->
			<input type="button" name="ver_tela1" id="ver_tela1" value="Dados do Ponto" >
			<input type="button" name="ver_tela2" id="ver_tela2" value="  Passeio  " style="display:none;">
			<input type="button" name="ver_tela3" id="ver_tela3" value="Interferências" style="display:none;">
			<input type="button" name="ver_tela4" id="ver_tela4" value="Croquis" style="display:none;">
			<input type="button" name="ver_tela5" id="ver_tela5" value="  GoogleMaps  ">
			<input type="button" value="Publicidade" onclick="location.href='home.php?action=pontos_publicidade&id_ponto=<?php echo $id_ponto;?>'"/>
			<input type="button" value="Ver Fotos" onclick="location.href='home.php?action=pontos_fotos&id_ponto=<?php echo $id_ponto;?>'"/>
			<input type="button" name="save" id="save"           value="     Salvar     ">
		</div>

	</fieldset>
</form>