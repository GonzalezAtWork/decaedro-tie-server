<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$db = Database::getInstance();

if ($_REQUEST['id_perfil'] == "new") {
	$id_perfil = -256;
	$nome = "";
} else {
	$id_perfil = $_REQUEST['id_perfil'];
	$query = "select * from perfis where ativo = TRUE and id_perfil = " . $id_perfil;
	$db->setQuery($query);
	$db->execute();
	$db_result = $db->getResultAsObject();
	$nome = $db_result->nome;
}
?>

<script type="text/javascript" src="javascript/perfis.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $id_perfil; ?>">

	<fieldset id="small">
	
		<legend>Dados do Registro</legend>
		
		<div id="fields">
			<div>
				<span>Nome do perfil:</span>
				<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $nome; ?>"></span>
			</div>
		</div>

		<?php
		#Carregando todas permissões e indicações das permissões selecionadas para teste perfil
		$query  = "select p.id_permissao as id_permissao, p.descricao as descricao, pp.id_permissao as id_selecionada from permissoes p ";
		$query .= "left outer join perfil_permissoes pp on (p.id_permissao = pp.id_permissao and pp.id_perfil = ".$id_perfil.") ";
		$query .= "order by 2;";

		$db->setQuery($query);
		$db->execute();
		$db_result = $db->getResultSet();
		
		#monta grid
		$grid  = '<table cellspacing="1" cellpadding="0" width="100%" border="0">';
		$grid .= '<thead><tr><th width="30">&nbsp;</th><th>Permissões</th></tr></thead>';
		$grid .= '<tbody>';
		
		foreach ($db_result as $row) {
			$grid .= '<tr class="tableRow"><td align="center"><input type="checkbox" name="id_permissao" id="id_permissao" value="'.$row["id_permissao"].'"';
			if ($row["id_permissao"] = $row["id_selecionada"]) {
				 $grid .= ' checked';
			}
			$grid .= '><td>'.$row["descricao"].'</td></tr>';
		}

		$grid .= '</td></tbody></table>';
		
		echo $grid;
		?>
		
		<div id="buttons">
			<input type="button" name="save" id="save" value="Salvar">
			<input type="button" name="cancel" id="cancel" value="Cancelar">
		</div>

	</fieldset>

</form>
