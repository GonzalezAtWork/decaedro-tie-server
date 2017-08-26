<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
?>
<script type="text/javascript" src="javascript/insere_foto.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<form name="form" id="form" method="post">
	<fieldset>
		<legend id="titulo">Nova Fotografia</legend>
		<div align="center" style="padding-top:10px;">
			<input	name="stamp"	id="stamp"	type="text"/>
			<input	name="nome"		id="nome"	type="text"/>
			<input	name="data"		id="data"	type="text"/>
			<table>
				<tr>
					<td>id_ponto:		</td><td><input		name="id_ponto"		id="id_ponto"		type="text"/></td>
					<td>id_ocorrencia:	</td><td><input		name="id_ocorrencia" id="id_ocorrencia"	type="text"/></td>
					<td>id_os:			</td><td><input		name="id_os"		id="id_os"			type="text"/></td>
				</tr>
				<tr>
					<td>id_vistoria:	</td><td><input		name="id_vistoria"	id="id_vistoria"	type="text"/></td>
					<td>id_item:		</td><td><input		name="id_item"		id="id_item"		type="text"/></td>
					<td>&nbsp;		</td><td>&nbsp;</td>
				</tr>
			</table>
			<textarea	name="foto"			id="foto"		style="display:none" ></textarea><br/><br/>
			<img		name="bmp_foto"		id="bmp_foto"	src="images/embranco.jpg" width="640" height="480"/><br/>
			<input		name="file_foto"	id="file_foto"	type="file"/>
		</div>
		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save"           value="     Salvar     ">
		</div>
	</fieldset>
</form>