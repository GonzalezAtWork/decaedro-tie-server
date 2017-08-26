<?php

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', true);

$critica = (isset($_REQUEST['critica']))?$_REQUEST['critica']:"";
if($critica == 'OK'){
	$critica = true;
}else{
	$critica = false;
}
$publicidade = (isset($_REQUEST['publicidade']))?$_REQUEST['publicidade']:"";
if($publicidade == 'OK'){
	$publicidade = true;
}else{
	$publicidade = false;
}

$db = Database::getInstance();
/*
$query .= " order by oss.data ";
$db->setQuery($query);
$db->execute();

$retorno= $db->getResultSet();
*/

$drop = new Dropdown();
$roteiro = $drop->getHTMLFromQuery('select id_roteiro as code, nome as label from roteiros', '' , true, 'id_roteiro', 'style="font-size:10pt; width:120px;"');
$gravidade = $drop->getHTMLFromQuery('select id_gravidade as code, nome as label from gravidades', '1', true, 'id_gravidade', ' style="font-size:10pt; width:250px;"');

$dia_semana = array("Domingo", "Segunda", "Terça" , "Quarta" , "Quinta", "Sexta", "Sábado");
//$data_atual = date("d/m/Y");
$data_atual = date("Y-m-d");

?>

<script type="text/javascript" src="javascript/oss.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
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

<form name="form" id="form" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="groups">
	<legend>Nova Ordem de Serviço<?php if($critica){echo ' - <b>URGENTE</b>';}?></legend>
	<div class="line">
		<label style="width:150px">Data:</label>
		<span class="field">&nbsp;<input type="text" name="data" id="data" class="small_field" style="width:100px;" value="<?php echo $data_atual;?>"></span>
	</div>
	<div class="line">
		<label style="width:150px">Previsão de Chuva:</label>
		<span class="field">&nbsp;<select name="chuva" id="chuva" style="font-size:10pt; width:120px;">
		<option value="f">Não</option>
		<option value="t">Sim</option>
		</select>
		</span>
	</div> 
	<div class="line">
		<label style="width:150px">Prioridade:</label>
		<span class="field">&nbsp;<select name="id_prioridade" id="id_prioridade" style="font-size:10pt; width:120px;" disabled>
		<option value="2">Normal</option>
		<option value="1" <?php if($critica){echo 'selected';}?>>Urgente</option>
		<option value="3" <?php if($publicidade){echo 'selected';}?>>Publicidade</option>
		</select>
		</span>
	</div> 
	<div class="line">
		<label style="width:150px">Referência:</label>
		<span class="field">&nbsp;<?php echo $gravidade;?></span>
	</div> 

	<div align="center" style="padding-top:10px;">
		<span><input type="button" name="insert" id="insert" value="Salvar"></span>
	</div>

	</fieldset>

</form>