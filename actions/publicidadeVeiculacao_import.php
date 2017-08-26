<style>
fieldset {
	width:740px;
}

label.left, span.right {
	display:inline-block;
	font-family:Verdana, Helvetica, sans-serif;
	font-size:9pt;
	height:28px;
	line-height:28px;
	vertical-align:top;
	min-width:160px;
}

span.right {
	padding-right:4px;
	text-align:right;
}

div {
	margin-bottom:8px;
}

div#textAreaContents {
	display:inline-block;
	font-size:9pt;
	height:120px;
	line-height:120px;
	vertical-align:top;
}

#fileContents {
	height:120px;
	line-height:18px;
	width:560px;
}

#fileContents:hover {
	padding:0px;
}
</style>

<script type="text/javascript" src="javascript/publicidadeVeiculacao_import.js"></script>

<form name="form" id="form" method="post">

	<fieldset>

		<legend>Importação de Roteiro de Veiculação de Publicidade</legend>

		<div>
			<label class="left">Dados relevantes:</label>
			<span class="right"><strong>Simak; Face; Imagem da Semana Atual;</strong></span>
		</div>

		<div>
			<label class="left">Número da semana:</label>
			<input type="text" id="week" name="week" class="tiny_field"/>
		</div>

		<div>
			<label class="left">Escolher Arquivo CSV:</label>
			<input accept=".csv" type="file" id="files" name="files[]"/>
		</div>

		<div id="textAreaContents">
			<label class="left">Conteúdo do Arquivo:</label>
			<textarea name="fileContents" id="fileContents" wrap="off"></textarea>
		</div>

		<div>
			<label class="left">Delimitador de campos:</label>
			<select name="delimiter" id="delimiter">
				<option value="0">Vírgula</option>
				<option value="1" selected>Ponto-e-vírgula</option>
				<option value="2">Dois-pontos</option>
				<option value="3">Tabulação</option>
				<option value="4">Espaço</option>
			</select>
		</div>

		<div align="center">
			<input type="button" name="import" id="import" value="Importar">
		</div>

	</fieldset>

</form>