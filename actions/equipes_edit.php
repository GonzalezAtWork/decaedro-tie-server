<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);


$db = Database::getInstance();

if (empty($_REQUEST['id_equipe'])) {

	$id_equipe = -256;
	$nome = "";
	$lavagem = 'f';

} else {

	$id_equipe = $_REQUEST['id_equipe'];
	$query = "select nome, lavagem from equipes where id_equipe = " . $id_equipe;
	$db->setQuery($query);
	$db->execute();
	$db_result = $db->getResultAsObject();
	$nome = $db_result->nome;
	$lavagem = $db_result->lavagem;

}
?>

<script type="text/javascript" src="javascript/equipes.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_equipe" id="id_equipe" value="<?php echo $id_equipe;?>">

	<fieldset id="groups" style="width:540px;">
	
		<legend>Dados da Equipe</legend>

		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $nome?>"></span>
		</div>
		
		<div class="line">
			<label>Lavagem:</label>
			<span class="field"><input type="checkbox" name="lavagem" id="lavagem" <?php echo ($lavagem == 't')?' checked ':'';?> value="TRUE"></span>
		</div>

		<?php
		$html = "";

		$query  = "select u.ativo, u.id_usuario, u.nome, p.nome as perfil, ue.id_equipe ";
		$query .= "from usuarios u ";
		$query .= "inner join perfis p on p.id_perfil = u.id_perfil ";
		$query .= "left join usuariosEquipes ue on u.id_usuario = ue.id_usuario ";
		$query .= "and ue.id_equipe = ".$id_equipe." ";
		$query .= "order by u.ativo desc, u.id_perfil, u.nome; ";

		$db->setQuery($query);
		$db->execute();

		$result = $db->getResultSet();
		$primeiro = "Membros:";

		foreach ($result as $row) {

			$html .= '<div class="line">';
			$html .= '<label>'.$primeiro.'</label>';
			$html .= '<span class="field">';
			$html .= '&nbsp;<input type="checkbox" name="usuarios[]" id="usuarios[]" ';

			if ($row["id_equipe"] == $id_equipe) {
				$html .= 'checked ';		
			}

			if ($row["ativo"] != "t") {
				$html .= 'disabled ';
			}

			$html .= 'value="'.$row["id_usuario"].'">&nbsp;';
			$html .= trim($row["nome"]).'&nbsp;<small>['.$row["perfil"].']';

			if ($row["ativo"] != "t") {
				$html .= '(inativo)';
			}

			$html .= '</small>';
			$html .= '</span>';
			$html .= '</div>';
			$primeiro = "&nbsp;";
		}

		echo $html;				
		?>

		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
			<input type="button" name="cancel" id="cancel" value="Cancelar">
		</div>

	</fieldset>

</form>