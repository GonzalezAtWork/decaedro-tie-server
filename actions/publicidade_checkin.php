<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$data_inicial = (isset($_REQUEST['data_inicial']))?$_REQUEST['data_inicial']: date('Y-m-d', strtotime(date("Y-m-d") . ' - 2 days'));
$data_final = (isset($_REQUEST['data_final']))?$_REQUEST['data_final']: date('Y-m-d', strtotime(date("Y-m-d") . ' + 1 days'));

$query  = "";
$query .= " select ";
$query .= "		fotografias.id_foto, ";

$query .= "		vistoriasitens.nome as item, ";
$query .= "		ocorrencias.semanapublicidade, ";
$query .= "		pontos.codigo_abrigo, ";
$query .= "		pontos.codigo_novo, ";
$query .= "		ocorrencias.nomeimagenspublicidade ,  ";
$query .= "		ocorrencias.id_ponto, ";
$query .= "		fotografias.id_item ";

$query .= "	from fotografias ";
$query .= " inner join ocorrencias on fotografias.id_ocorrencia = ocorrencias.id_ocorrencia ";
$query .= " inner join vistoriasitens on fotografias.id_item = vistoriasitens.id_item ";
$query .= " inner join pontos on ocorrencias.id_ponto = pontos.id_ponto ";
$query .= " where ocorrencias.nomeimagenspublicidade is not null ";

//$query .= " and ocorrencias.dt_lastupdate > (CURRENT_DATE - INTERVAL '2 day')::date ";

$query .= " and ocorrencias.dt_lastupdate >= ('". date("Y-m-d", strtotime( $data_inicial ) ) ."')::date ";
$query .= " and ocorrencias.dt_lastupdate <= ('". date("Y-m-d", strtotime( $data_final ) ) ."')::date ";

$query .= " order by ocorrencias.id_ocorrencia desc, fotografias.id_item desc ";

//echo $query;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultSet();
?>
<script type="text/javascript" src="includes/jszip.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <script>
  $(function() {
    $( "#data_final" ).datepicker({
      showOn: "button",
      buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
      buttonImageOnly: true,
	  dateFormat: "yy-mm-dd"
    });
    $( "#data_inicial" ).datepicker({
      showOn: "button",
      buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
      buttonImageOnly: true,
	  dateFormat: "yy-mm-dd"
    });
  });
  </script>
<script language="javascript">
	function create_zip() {
		errorMessage = '<div id="errorMessage"><div align="center">Processando...</div></div>';
		TINY.box.show({html:errorMessage,close:true,animate:true,width:480,height:100});
		setTimeout(exec_create_zip,1000);
	}
	function exec_create_zip() {
		var c = document.getElementById("myCanvas");
		var ctx = c.getContext("2d");
		var zip = new JSZip();
		$( "img" ).each(function( index ) {
			if( $(this).attr('foto') == 'ok' ){
				if( $('#chk_' + $(this).attr('id')).attr('checked') == 'checked' ){
					var img = document.getElementById($(this).attr('id'));
					ctx.drawImage(img, 10, 10);
					var fileName = $(this).attr('id') + ".jpg";
					zip.file(fileName, c.toDataURL().split('data:image/png;base64,').join(''), {base64: true});
				}
			}
		});
		var content = zip.generate();
		TINY.box.hide();
		var blobLink = document.getElementById('blob');
		blobLink.download = "checkin.zip";
		blobLink.href = window.URL.createObjectURL(zip.generate({type:"blob"}));
		blobLink.click();
	}
</script>
<form name="form" id="form" method="post" action="home.php?action=publicidade_checkin">
	<fieldset id="groups">
		<legend>Check-In Fotográfico</legend>
		<div align="center" style="padding-top:10px;">
			<label style="width:50px">Início:</label>
				<span class="field"><input type="text" name="data_inicial" id="data_inicial" class="small_field" style="width:100px;" value="<?php echo $data_inicial;?>"></span>
			<label style="width:50px">Fim:</label>
				<span class="field"><input type="text" name="data_final" id="data_final" class="small_field" style="width:100px;" value="<?php echo $data_final;?>"></span>
			<input type="button" onclick="form.submit()" value="Atualizar"/>
		</div>
<?php
	echo "<table>";
	$id_ponto = 0;
	foreach ($db_result as $foto) {
		$motivo = $foto['nomeimagenspublicidade'];
		$motivo = explode("|".$foto['id_item'].",", "VAZIO|".$motivo);
		$motivo = explode(";", $motivo[1]);
		$motivo = explode("|", $motivo[1]);
		$motivo = $motivo[0];

		$filename = $foto['semanapublicidade'] . "_" . $foto['codigo_novo'] . "_" . str_replace("CAIXA ","",str_replace(" FACE ","_",str_replace("NTERNA","",str_replace("XTERNA","",$foto["item"]))));

		if($id_ponto != $foto['id_ponto']){
			echo "<tr>";
			echo "<td>Simak: ".$foto['codigo_abrigo']."<br/>&nbsp;<br/><input type='checkbox' name='chk_".$filename."' id='chk_".$filename."'/> Exportar<br/>&nbsp;<br/><img width='150' src='imagem.php?nome=". base64_encode($motivo) ."'/></td>";
		}
		echo "<td>";
		echo "<img width='400' foto='ok' id='".$filename."' src='checkin.php?id_foto=".$foto['id_foto']."'/>";
		echo "</td>";
		if($id_ponto == $foto['id_ponto']){
			echo "<td>Simak: ".$foto['codigo_abrigo']."<br/>&nbsp;<br/><input type='checkbox' name='chk_".$filename."' id='chk_".$filename."'/> Exportar<br/>&nbsp;<br/><img width='150' src='imagem.php?nome=". base64_encode($motivo) ."'/></td>";
			echo "</tr>";
		}
		$id_ponto = $foto['id_ponto'];
	}
	echo "</table>";
?>
<?php 		if ($db->getRows() > 0) { ?>
		<div align="center" style="padding-top:10px;">
			<input type="button" onclick="create_zip()" name="save" id="save" value="Exportar ZIP"/>
		</div>
<?php } ?>
	</fieldset>
	<canvas style="display:none" id="myCanvas" width="667" height="500"></canvas>
	<a href="#" id="blob" style="display:none">click to download</a>
</form>