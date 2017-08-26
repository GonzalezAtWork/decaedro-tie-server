<script type="text/javascript" src="javascript/pontos_import.js"></script>
<form name="form" id="form" method="post">
	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">
	<fieldset id="groups">
		<legend>Importação de Pontos de Parada</legend>
		<div style="padding-top:20px;">
			<div style="padding-top:10px;">
				<span class="left_label" style="width:200px">Estrutura de Cabeçalho:</span>
				IDABRIGO;ENDERECO;NOTIMA;TIPO;ROTEIRO;LATITUDE;LONGITUDE
				<br/>&nbsp;<br/>
				<span class="left_label" style="width:200px">Escolher Arquivo CSV:</span>
				<input accept=".csv" type="file" id="files" name="files[]" />
				<br/>&nbsp;<br/>
				<span class="left_label" style="width:200px">Conteúdo do Arquivo:</span>
				<textarea wrap="off" id="list" name="list" style="width:650px;height:150px;"></textarea>
			</div>
			<div align="center" style="padding-top:10px;">
				<input type="button" name="enviar" id="enviar" value="Enviar">
			</div>
		</div>
	</fieldset>
</form>