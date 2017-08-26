<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_roteiro = $_REQUEST['id_roteiro'];
$query = 'select * from roteiros where id_roteiro = ' . $id_roteiro;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();

?>

<script type="text/javascript" src="javascript/roteiros.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_roteiro" id="id_roteiro" value="<?php echo $db_result->id_roteiro; ?>">

	<fieldset id="groups">
	
		<legend>Dados do Roteiro</legend>
		<div class="line">
			<label>Nome:</label>
			<span class="field"><input type="text" name="nome" id="nome" class="medium_field" value="<?php echo $db_result->nome?>"></span>
		</div>
		<div class="line">
			<label>Noturno:</label>
			<span class="field"><input type="checkbox" name="noturno" id="noturno" <?php echo ($db_result->noturno == 't')?' checked ':'';?> value="TRUE"></span>
			<label style="width:80px">Vistoria:</label>
			<span class="field"><input type="checkbox" name="vistoria" id="vistoria" <?php echo ($db_result->vistoria == 't')?' checked ':'';?> value="TRUE"></span>
			<label style="width:100px">Manutenção:</label>
			<span class="field"><input type="checkbox" name="manutencao" id="manutencao" <?php echo ($db_result->manutencao == 't')?' checked ':'';?> value="TRUE"></span>
			<label style="width:100px">Publicidade:</label>
			<span class="field"><input type="checkbox" name="publicidade" id="publicidade" <?php echo ($db_result->publicidade == 't')?' checked ':'';?> value="TRUE"></span>
			<label style="width:100px">Lavagem:</label>
			<span class="field"><input type="checkbox" name="lavagem" id="lavagem" <?php echo ($db_result->lavagem == 't')?' checked ':'';?> value="TRUE"></span>
		</div>
		<div class="line">
			<label>Frequência:</label>
			<span class="field"><input type="text" name="frequencia" id="frequencia" class="small_field" value="<?php echo $db_result->frequencia?>"></span>
		</div>
		<br/>&nbsp;

		<?php
		$html = "";

		#Conecta na base de dados
		$db = Database::getInstance();

		$query  = " select b.*, br.id_roteiro, z.nome as zona ";
		$query .= " from bairros b ";
		$query .= " left join zonas z on b.id_zona = z.id_zona ";
		
		$query .= " left join bairrosRoteiro br  ";
		$query .= "		on b.id_bairro = br.id_bairro ";
		$query .= "		and br.id_roteiro = ". $db_result->id_roteiro ;

		//$query .= " where b.ativo = TRUE ";
		$query .= " order by b.ativo desc, z.id_zona, b.nome; ";

		////echo $query;

		$db->setQuery($query);
		$db->execute();

		$result = $db->getResultSet();
		$html .= '<div align="center">';
		$html .= '<table width="700" border="0">';
		$html .= '<tr>';
		$zona = "";
		$contador = 0;
		foreach ($result as $row) {
			if($row["zona"] != $zona){
				$zona = $row["zona"];
				$html .= '</table>';
				$html .= '<table width="700" border="0">';
				$html .= '<tr><td align="center" colspan="5"><a href="javascript:mostraZona('.$row["id_zona"].')"><b>ZONA: '. $zona .'</b></a></td></tr>';
				$html .= '</table>';
				$html .= '<table style="display:none" id="zona_'. $row["id_zona"] .'" width="700" border="1">';
				$html .= '<tr>';			
				$contador = 1;
			}else{
				$contador = $contador + 1;			
			}
			$html .= '<td align="left" width="230">';
			$html .= '		<input type="checkbox" name="bairros[]" id="bairros[]"';
			if($row["id_roteiro"] == $db_result->id_roteiro){
				$html .= ' checked ';		
			}
			if($row["ativo"] != "t"){
				$html .= 'disabled';
			}
			$html .= '       value="'. $row["id_bairro"] .'">';
			$html .= ' '.$row["nome"];
			if($row["ativo"] != "t"){
				$html .= '<small>(inativo)</small>';
			}
			$html .= '</td>';
			if($contador == 3){
				$contador = 0;
				$html .= '</tr><tr>';				
			}
		}
		$html .= '</tr>';
		$html .= '</table>';
		$html .= '</div>';
		/*
		$primeiro = "Bairros:";
		foreach ($result as $row) {
		$html .= '<div class="line">';
		$html .= '	<label>'. $primeiro .'</label>';
		$html .= '	<span class="field">';
		$html .= '		<input type="checkbox" name="bairros[]" id="bairros[]"';
		if($row["id_roteiro"] == $db_result->id_roteiro){
			$html .= ' checked ';		
		}
		if($row["ativo"] != "t"){
			$html .= 'disabled';
		}
		$html .= '       value="'. $row["id_bairro"] .'">';
		$html .= ' '.$row["nome"].' <small>('.$row["zona"].')';
		if($row["ativo"] != "t"){
			$html .= '(inativo)';
		}
		$html .= ' </small>';
		$html .= '	</span>';
		$html .= '</div>';
		$primeiro = "&nbsp;";
		}
		*/
		echo $html;				
		?>

		<div align="center" style="padding-top:10px;">
			<input type="button" name="save" id="save" value="Salvar">
			<input type="button" name="mapa" id="mapa" value="   Mapa   ">
		</div>

	</fieldset>

</form>