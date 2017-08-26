<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

#Se tem id_veiculacao é edição, senão não tem
if (empty($_REQUEST['id_veiculacao'])) {

	$id_veiculacao = "";
	$simak = "";
	$semana = "";
	$ano = "";
	$face = "";
	$imagem = "";

	$firstBlank = TRUE;

} else {

	$id_veiculacao = $_REQUEST['id_veiculacao'];

	#Conecta na base de dados
	$db = Database::getInstance();

	$query  = " SELECT simak, semana, ano, face, nome_imagem ";
	$query .= " from publicidadeVeiculacao ";
	$query .= " WHERE ativo = true ";
	$query .= " and id_veiculacao = " . $id_veiculacao;

	#Executa query
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultAsObject();

	$simak = $dados->simak;
	$semana = $dados->semana;
	$ano = $dados->ano;
	$face = $dados->face;
	$imagem = $dados->nome_imagem;

	$firstBlank = FALSE;

}
$drop = new Dropdown();
$select = $drop->getHTMLFromQuery('SELECT nome AS code, nome AS label FROM publicidadeImagens ORDER BY 1', $imagem, $firstBlank, 'nome_imagem');
?>

<script type="text/javascript" src="javascript/publicidadeVeiculacao_edit.js"></script>

<form method="post" name="form" id="form">

	<input type="hidden" name="id_veiculacao" id="id_veiculacao" value="<?php echo $id_veiculacao;?>">

	<fieldset id="small">

		<legend>Dados da Veiculação</legend>

		<div class="line">
			<label>Simak:</label>
			<input type="text" name="simak" id="simak" class="date_field" value="<?php echo $simak;?>">
		</div>

		<div class="line">
			<label>Semana:</label>
			<input type="text" name="semana" id="semana" class="tiny_field" value="<?php echo $semana;?>">
		</div>

		<div class="line">
			<label>Face:</label>
			<input type="text" name="face" id="face" class="tiny_field" value="<?php echo $face;?>">
		</div>

		<div class="line">
			<label>Imagem:</label>
			<?php echo $select ?>
		</div>

		<div id="buttons">
			<span><input type="button" name="save" id="save" value="Salvar"></span>
			<span><input type="button" name="cancel" id="cancel" value="Cancelar"></span>
		</div>

	</fieldset>

</form>