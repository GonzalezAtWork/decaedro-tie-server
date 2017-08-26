<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_padrao = $_REQUEST['id_padrao'];
$query = 'select * from pontosPadrao where id_padrao = ' . $id_padrao;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();

$drop = new Dropdown();

$tipo = $drop->getHTMLFromQuery('select id_tipo as code, nome as label from pontosTipo', $db_result->id_tipo, true, 'id_tipo', 'style="font-size:10pt; width:150px;"');
?>

<script type="text/javascript" src="javascript/pontosPadrao.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_padrao" id="id_padrao" value="<?php echo $db_result->id_padrao; ?>">

	<fieldset id="groups">
	
		<legend>Dados do Registro</legend>
		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $db_result->nome?>"></span>
		</div>		
		<div class="line">
			<label>Tipo de Ponto:</label>
			<span><?php echo $tipo?></span>
		</div>
		<div class="line">
			<label>Obs. Telhado:</label>
			<span class="field"><input type="text" name="telhado" id="telhado" class="medium_field" value="<?php echo $db_result->telhado?>"></span>
		</div>
		<div class="line">
			<label>Qtd. de Módulos:</label>
			<span class="field"><input type="text" name="qtd_modulos" id="qtd_modulos" class="small_field" value="<?php echo $db_result->qtd_modulos?>"></span>
		</div>
		<div style="padding-top:10px;">
			<table cellspacing="1" cellpadding="0" width="100%" border="0">
			<tr>
			<td align="center"><label>Croquis:</label><br/>&nbsp;<br/>
				<img name="bmp_croquis" id="bmp_croquis" src="<?php echo ($db_result->croquis == '')?'images/embranco.jpg':'data:image/jpeg;base64,'.$db_result->croquis; ?>" width="320" height="240"/>
				<textarea style="display:none"  name="croquis" id="croquis"><?php echo $db_result->croquis?></textarea><br/>&nbsp;<br/>
				<input type="file" name="file_croquis" id="file_croquis"/>
			</td>
			<td align="center"><label>Fotografia:</label><br/>&nbsp;<br/>
				<img name="bmp_foto" id="bmp_foto" src="<?php echo ($db_result->foto == '')?'images/embranco.jpg':'data:image/jpeg;base64,'.$db_result->foto; ?>" width="320" height="240"/>
				<textarea style="display:none" name="foto" id="foto"><?php echo $db_result->foto?></textarea><br/>&nbsp;<br/>
				<input type="file" name="file_foto" id="file_foto"/>
			</td>
			</tr>
			</table>
		</div>	
		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
		</div>
	</fieldset>
</form>