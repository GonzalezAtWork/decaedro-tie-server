<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

#Se não tem id_imagem ou se o id_imagem é nova imagem
if (empty($_REQUEST['id_imagem'])) {

	$id_imagem = "";
	$periodo_inicio = "";
	$periodo_fim = "";
	$descricao = "";
	$observacao = "";

} else {

	$id_imagem = $_REQUEST['id_imagem'];

	#Conecta na base de dados
	$db = Database::getInstance();

	$query  = " SELECT ";
	$query .= " to_char(periodo_inicio, 'DD/MM/YYYY') as inicial, ";
	$query .= " to_char(periodo_fim, 'DD/MM/YYYY') as final, ";
	$query .= " nome, ";
	$query .= " observacao, ";
	$query .= " imagem ";
	$query .= " FROM publicidadeimagens ";
	$query .= " where ativo = true ";
	$query .= " and id_imagem = " . $id_imagem;

	#Executa query
	$db->setQuery($query);
	$db->execute();
	$imagem = $db->getResultAsObject();

	$periodo_inicio = $imagem->inicial;
	$periodo_fim = $imagem->final;
	$nome = $imagem->nome;
	$observacao = $imagem->observacao;
	$imagem = $imagem->imagem;
}

$periodo_inicio = '22-11-03';
$periodo_fim = '22-11-03';
?>

<script type="text/javascript" src="javascript/publicidadeImagens_edit.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css"/>

<form method="post" name="form" id="form">

	<input type="hidden" name="id_imagem" id="id_imagem" value="<?php echo $id_imagem;?>">

	<fieldset id="small">

		<legend>Dados da Imagem</legend>

		<div class="line">
			<label>Período de veiculação:</label>
			<span class="field">
				<input type="text" name="periodo_inicio" id="periodo_inicio" class="date_field" value="<?php echo $periodo_inicio;?>">
				&nbsp;até&nbsp;
				<input type="text" name="periodo_fim" id="periodo_fim" class="date_field" value="<?php echo $periodo_fim;?>">
			</span>
		</div>

		<div class="lineTextArea">
			<label class="labelTextArea">Observação:</label>
			<span><textarea name="observacao" id="observacao" rows="4" cols="36"><?php echo $observacao;?></textarea></span>
		</div>

		<?php
		if (empty($id_imagem)) {
			?>
			<div class="line">
				<label>Imagem:</label>
				<span class="field"><input type="file" name="imagem" id="imagem"></span>
			</div>
			<div id="thumbnail"><img name="toShow" id="toShow" width="167"></div>
			<?php
		} else {
			?>
			<div class="line">
				<label>Nome:</label>
				<span class="field"><input type="text" name="nome" id="nome" value="<?php echo $nome;?>"></span>
			</div>
			<div class="line">
				<label>Arquivo:</label>
				<span class="field"><input type="file" name="imagem" id="imagem"></span>
			</div>
			<div id="thumbnail"><img name="toShow" id="toShow" width="167" src="data:image/jpeg;base64,<?php echo $imagem;?>"></div>
			<?php
		}
		?>

		<div id="buttons">
			<span><input type="button" name="save" id="save" value="Salvar"></span>
			<span><input type="button" name="cancel" id="cancel" value="Cancelar"></span>
		</div>

	</fieldset>

</form>