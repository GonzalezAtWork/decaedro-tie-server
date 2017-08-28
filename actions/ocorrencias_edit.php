<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$retorno_id_os = (isset($_REQUEST['id_os']))?$_REQUEST['id_os']:"";

$id_ocorrencia = (isset($_REQUEST['id_ocorrencia']))?$_REQUEST['id_ocorrencia']:0;
$query = 'select * from ocorrencias where id_ocorrencia = ' . $id_ocorrencia;

$db = Database::getInstance();

if($id_ocorrencia != 0){
	$query = "";
	$query .= " select pontos.*, ocorrencias.*, ocorrencias.data as dt_ocor, roteiros.nome as roteiro, vistorias.data as vis_data, usuarios.nome as equipe from ocorrencias ";
	$query .= " left join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
	$query .= " left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
	$query .= " left join vistorias on vistorias.id_vistoria = ocorrencias.id_vistoria ";
	$query .= " left join oss on oss.id_os = ocorrencias.id_os ";
	$query .= " left join usuarios on ocorrencias.id_equipe = usuarios.id_usuario ";
	$query .= " where id_ocorrencia = ". $id_ocorrencia .";";

	//echo $query;

	$db->setQuery($query);
	$db->execute();
	$db_result = $db->getResultAsObject();

	$id_ocorrencia = $db_result->id_ocorrencia;
	$id_equipe = $db_result->id_equipe;
	$id_roteiro = $db_result->id_roteiro;
	$itensvistoria = $db_result->itensvistoria;
	$itensmanutencao = $db_result->itensmanutencao;
	$dt_ocor = $db_result->dt_ocor;
	$codigo_abrigo = $db_result->codigo_abrigo;
	$endereco = $db_result->endereco;
	$id_vistoria = $db_result->id_vistoria;
	$vistoriada = $db_result->vistoriada;
	$id_os = $db_result->id_os;
	$executada = $db_result->executada;
	$observacaovistoria = $db_result->observacaovistoria;
	$fotovistoria = $db_result->fotovistoria;
	$observacaomanutencao = $db_result->observacaomanutencao;
	$fotomanutencao = $db_result->fotomanutencao;
	$observacao = $db_result->observacao;
	$id_ponto = $db_result->id_ponto;
	$nomeImagensPublicidade = $db_result->nomeimagenspublicidade;
}else{
	$id_ocorrencia = 0;
	$id_equipe = "";
	$id_roteiro = "";
	$itensvistoria = "";
	$itensmanutencao = "";
	$dt_ocor = "";
	$codigo_abrigo = "";
	$endereco = "";
	$id_vistoria = "";
	$vistoriada = "";
	$id_os = "";
	$executada = "";
	$observacaovistoria = "";
	$fotovistoria = "";
	$observacaomanutencao = "";
	$fotomanutencao = "";
	$observacao = "";
	$id_ponto = "";
	$nomeImagensPublicidade = "";
}

$drop = new Dropdown();
$equipe = $drop->getHTMLFromQuery('select id_usuario as code, nome as label from usuarios where id_perfil = 2', $id_equipe , true, 'id_equipe', 'style="font-size:10pt; width:150px;"');
$roteiro = $drop->getHTMLFromQuery('select id_roteiro as code, nome as label from roteiros', $id_roteiro  , true, 'id_roteiro', ' disabled style="font-size:10pt; width:150px;"');


?>

<script type="text/javascript" src="javascript/ocorrencias.js"></script>

<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <style>
  #sortable1, #sortable2, #sortable3, #sortable4 { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 420px;}
  #sortable1 li, #sortable2 li, #sortable3 li, #sortable4 li { margin: 5px; padding: 5px; font-size: 1em; width: 400px; }
  </style>
  <script>
  $(function() {
    $( "ul.droptrue" ).sortable({
      connectWith: "ul"
    });
 
    $( "ul.dropfalse" ).sortable({
      connectWith: "ul",
      dropOnEmpty: false
    });
	$( "#sortable1" ).sortable({ cancel: ".ui-state-disabled" });
    $( "#sortable1" ).disableSelection();
	$( "#sortable2" ).sortable({ cancel: ".ui-state-disabled" });
    $( "#sortable2" ).disableSelection();
	$( "#sortable3" ).sortable({ cancel: ".ui-state-disabled" });
    $( "#sortable3" ).disableSelection();
	$( "#sortable4" ).sortable({ cancel: ".ui-state-disabled" });
    $( "#sortable4" ).disableSelection();
  });
  </script>
<form name="form" id="form" method="post">

	<input type="hidden" name="retorno_id_os" id="retorno_id_os" value="<?php echo $retorno_id_os; ?>">
	<input type="hidden" name="id_ocorrencia" id="id_ocorrencia" value="<?php echo $id_ocorrencia; ?>">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="dados">
		<?php if($id_ocorrencia != 0){?>
			<legend>Ocorrência - Dados</legend>
		<?php }else{?>
			<legend>Nova Não Conformidade - Dados</legend>
		<?php } ?>

		<?php if($id_ocorrencia != 0){?>
			<div class="line">
				<label>Código:</label>
				<span class="field"><input disabled type="text" class="medium_small" value="<?php echo $id_ocorrencia?>"></span>
			</div>
			<div class="line">
				<label>Data:</label>
				<span class="field"><input disabled type="text" class="medium_small" value="<?php echo $dt_ocor  ?>"></span>
			</div>
		<?php } ?>
		<input type="hidden" name="id_ponto" id="id_ponto" value="<?php echo $id_ponto?>" >
		<?php if($id_ocorrencia != 0){?>
			<div class="line">
				<label>Simak:</label>
				<span class="field"><input disabled type="text" class="medium_small" value="<?php echo $codigo_abrigo?>"></span>
			</div>
		<?php }else{?>
			<div class="line">
				<label>Simak:</label>
				<span class="field"><input type="text" name="simak" id="simak" class="medium_small" value="<?php echo $codigo_abrigo?>"> <img onclick="checa_simak()" width="18" height="18" src="http://png.findicons.com/files/icons/2226/matte_basic/32/search.png" title="Checar Simak"/></span>
			</div>
		<?php } ?>
		<div class="line">
			<label>Endereço:</label>
			<span class="field"><input disabled name="endereco" id="endereco" type="text" class="medium_field" value="<?php echo $endereco?>"></span>
		</div>
		<div class="line">
			<label>Roteiro:</label>
			<span class="field"><?php echo $roteiro;?></span>
		</div>
		<?php if($id_ocorrencia != 0){?>
			<div class="line">
				<label>Ordem de Serviço:</label>
				<span class="field"><input disabled type="text" class="medium_field" value="<?php echo $id_os?>"></span>
			</div>
		<?php } ?>
		<div>
			<label>Observações:</label>
			<span class="field"><textarea rows="5" cols="80" name="observacao" id="observacao"><?php echo $observacao?></textarea></span>
		</div>
		
		<div align="center" style="padding-top:10px;">
			<input type="button" value="Dados" onclick="mostraTela('dados')">
			<input type="button" value="Vistoria" onclick="mostraTela('vistoria')">
<?php if($id_ocorrencia != 0){ ?>
			<input type="button" value="Manutenção" onclick="mostraTela('manutencao')">
<?php } ?>
			<input type="button" value="Salvar" onclick="salvar_ocorrencia()">
		</div>

	</fieldset>
	<fieldset id="vistoria" style="display:none">
		<?php if($id_ocorrencia != 0){?>
			<legend>Ocorrência - Vistoria</legend>
		<?php }else{?>
			<legend>Nova Não Conformidade - Vistoria</legend>
		<?php } ?>

<?php 
if( $nomeImagensPublicidade != "" ){
	//50,;brahma nova|51,;brahma nova
	$trocas = explode("|",$nomeImagensPublicidade);
	foreach($trocas as $item){
		//51,brahma velha;brahma nova
		$codigo = explode(",",$item);
		$antiga = explode(";", $codigo[1]);
		$nova   = $antiga[1];
		$antiga = $antiga[0];
		$codigo = $codigo[0];

		$query  = "";
		$query .= " select ";
		$query .= " 	vistoriasItens.nome,  ";
		$query .= " 	antiga.imagem as imagem_antiga,  ";
		$query .= " 	antiga.nome as nome_antiga,  ";
		$query .= " 	nova.imagem as imagem_nova,  ";
		$query .= " 	nova.nome as nome_nova  ";
		$query .= " from vistoriasItens ";
		$query .= " left join (select ". $codigo ." as id_item, nome, imagem from publicidadeimagens where nome = '". $antiga ."') as antiga on vistoriasItens.id_item = antiga.id_item ";
		$query .= " left join (select ". $codigo ." as id_item, nome, imagem from publicidadeimagens where nome = '". $nova ."') as nova on vistoriasItens.id_item = nova.id_item ";
		$query .= " where vistoriasItens.id_item = ". $codigo ." ";

		//echo $query;
		$db->setQuery($query);
		$db->execute();
		$db_troca = $db->getResultAsObject();
		
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>";
		echo "<tr>";
		echo "<td colspan='2' align='center'>". $db_troca->nome ."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align='center'>Imagem Anterior: ". $db_troca->nome_antiga ."</td>";
		echo "<td align='center'>Novo Imagem: ". $db_troca->nome_nova ."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align='center'><img width='200' src='data: image/jpeg;base64,". $db_troca->imagem_antiga ."'/></td>";
		echo "<td align='center'><img width='200' src='data: image/jpeg;base64,". $db_troca->imagem_nova ."'/></td>";
		echo "</tr>";
		echo "</table>";
		echo "<br/>";
	}
}?>

		<table cellspacing='0' cellpadding='0' border='0' width="100%" <?php if( $nomeImagensPublicidade != "" ){?>style="display:none"<?php }?>><tr>
		
		<?php if($vistoriada != 't'){?>
	<td>
			<center>TODOS OS ITENS</center><br/>
			<div style="width: 450px; height: 200px; overflow:auto">
				<ul id="sortable1" class="droptrue">
		<?php
		$query = "";
		$query .= " select * from vistoriasItens ";
		if($itensvistoria != ""){
			$query .= " where id_item not in (". $itensvistoria ."); ";
		}
		$db->setQuery($query);
		$db->execute();
		$itens_result = $db->getResultSet();
		if($itens_result){
			foreach ($itens_result as $bla) {
					echo '<li id="'. $bla["id_item"] .'" class="ui-state-default">';
					echo $bla["nome"];
					echo '</li>';
					
			}
		}
		?>
		</ul>
			</div>
	</td>
<td width="100%">&nbsp;</td>
<td>
<center>VISTORIAR</center><br/>
<?}else{?>
<ul id="sortable1" class="droptrue" style="display:none">
<td align="center">
<center>ITENS VISTORIADOS</center><br/>
<?};?>

		<div style="width: 450px; height: 200px; overflow:auto">
			<ul id="sortable2" class="droptrue">
	<?php
	$query = "";
	$query .= " select * from vistoriasItens ";
	if($itensvistoria != ""){
		$query .= " where id_item in (". $itensvistoria ."); ";
	}else{
		$query .= " where 1 = 0; ";
	}
	$db->setQuery($query);
	$db->execute();
	$itens_result = $db->getResultSet();
	if($itens_result){
		foreach ($itens_result as $bla) {
				echo '<li id="'. $bla["id_item"] .'" class="ui-state-default">';
				echo $bla["nome"];
				echo '</li>';
		}
	}
	?>
	</ul>
		</div>
		</td>
		</tr></table>
		<div class="line">
			<label>Executada:</label>
			<span class="field"><input name="vistoriada" id="vistoriada" type="checkbox" value="TRUE" <?php if($id_ocorrencia == 0 ){echo 'disabled selected checked';};?> <?php if($vistoriada == 't' ){echo 'selected checked';};?>></span>
		</div>
		<div>
			<label>Vistoria:</label>
			<?php if($id_vistoria != ""){?>
				<span class="field"><input disabled type="text" class="medium_field" value="<?php echo $id_vistoria?>"></span>
			<?php }else{ ?>
				<?php if($nomeImagensPublicidade != ""){?>
					<span class="field"><input disabled type="text" class="medium_field" value="PUBLICIDADE"></span>
				<?php }else{ ?>
					<span class="field"><input disabled type="text" class="medium_field" value="NÃO CONFORMIDADE"></span>
				<?php } ?>
			<?php } ?>
		</div>
		<div>
			<label>Observações:</label>
			<textarea rows="5" cols="80" name="observacaoVistoria" id="observacaoVistoria"><?php echo $observacaovistoria?></textarea>
		</div>
		<?php
		if (($id_vistoria != "" || ( $id_vistoria == "" && $id_os == "" )) && $id_ocorrencia != 0 ){
			$query = "";
			$query .= " select nome, id_foto from fotografias ";
			if($id_vistoria == ""){
				$q_id_vistoria = "null";
			}else{
				$q_id_vistoria = $id_vistoria;
			}
			$query .= " where ( id_vistoria = ". $q_id_vistoria ." or (id_vistoria = 0 and id_os = 0 ) ) and id_ocorrencia = ". $id_ocorrencia ."; ";
			
			$db->setQuery($query);
			$db->execute();
			$fotos_result = $db->getResultSet();
			if($fotos_result){
				echo '<div align="center">';
				foreach ($fotos_result as $foto) {
					echo '<img ';
					echo ' download="' . $foto["nome"] . '.jpg" ';
					//echo ' src="data:image/jpeg;base64,' . $foto["base64"] . '" ';
					echo ' src="http://tie4.decaedro.net/foto.php?id_foto='. $foto["id_foto"] .'"';
					echo ' height="500" ';
					echo ' /><br/>&nbsp;<br/>&nbsp;';
				}
				echo '</div>';
			}
		}
		?>
		<div align="center">
			<input type="button" value="Dados" onclick="mostraTela('dados')">
			<input type="button" value="Vistoria" onclick="mostraTela('vistoria')">
<?php if($id_ocorrencia != 0){ ?>
			<input type="button" value="Manutenção" onclick="mostraTela('manutencao')">
<?php } ?>
			<input type="button" value="Salvar" onclick="salvar_ocorrencia()">
		</div>

	</fieldset>
	<fieldset id="manutencao" style="display:none">

		<?php if($id_ocorrencia != 0){?>
			<legend>Ocorrência - Manutenção</legend>
		<?php }else{?>
			<legend>Nova Não Conformidade - Manutenção</legend>
		<?php } ?>
		<table width="100%" cellspacing='0' cellpadding='0' border='0'><tr>
		
<?php if($executada != 't'){?>
<td>
		<center>TODOS OS ITENS</center><br/>
		<div style="width: 450px; height: 200px; overflow:auto">
			<ul id="sortable3" class="droptrue">
	<?php
	$query = "";
	$query .= " select * from vistoriasItens ";
	if($itensmanutencao != ""){
		$query .= " where id_item not in (". $itensmanutencao ."); ";
	}
	$db->setQuery($query);
	$db->execute();
	$itens_result = $db->getResultSet();
	if($itens_result){
		foreach ($itens_result as $bla) {
				echo '<li id="'. $bla["id_item"] .'" class="ui-state-default">';
				echo $bla["nome"];
				echo '</li>';
				
		}
	}
	?>
	</ul>
		</div>
		</td>
<td width="100%">&nbsp;</td>
<td>
<center>REALIZADOS</center><br/>
<?}else{?>
<ul id="sortable3" class="droptrue" style="display:none">
<td align="center">
<center>REALIZADOS</center><br/>
<?};?>
		<div style="width: 450px; height: 200px; overflow:auto">
			<ul id="sortable4" class="droptrue">
	<?php
	$query = "";
	$query .= " select * from vistoriasItens ";
	if($itensmanutencao != ""){
		$query .= " where id_item in (". $itensmanutencao ."); ";
	}else{
		$query .= " where 1 = 0; ";
	}
	$db->setQuery($query);
	$db->execute();
	$itens_result = $db->getResultSet();
	if($itens_result){
		foreach ($itens_result as $bla) {
				echo '<li id="'. $bla["id_item"] .'" class="ui-state-default">';
				echo $bla["nome"];
				echo '</li>';
				
		}
	}
	?>
	</ul>
		</div>
		</td>
		</tr></table>
		<br/>
		
		<div class="line">
			<label>Executada:</label>
			<span class="field"><input name="executada" id="executada" type="checkbox" value="TRUE" <?php if($executada == 't'){echo 'selected checked';};?>></span>
		</div>
		<div class="line">
			<label>Eq. Manutenção:</label>
			<span class="field"><?php echo $equipe;?></span>
		</div>
		<div>
			<label>Observações:</label>
			<textarea rows="5" cols="80" name="observacaoManutencao" id="observacaoManutencao"><?php echo $observacaomanutencao?></textarea>
		</div>
		<?php
		if ($id_os != "" && $id_ocorrencia != 0 ){
			$query = "";
			$query .= " select nome, id_foto from fotografias ";
			$query .= " where id_os = ". $id_os ." and id_ocorrencia = ". $id_ocorrencia ."; ";
			$db->setQuery($query);
			$db->execute();
			$fotos_result = $db->getResultSet();
			if($fotos_result){
				echo '<div align="center">';
				foreach ($fotos_result as $foto) {
					echo '<img ';
					echo ' download="' . $foto["nome"] . '.jpg" ';
					//echo ' src="data:image/jpeg;base64,' . $foto["base64"] . '" ';
					echo ' src="http://tie4.decaedro.net/foto.php?id_foto='. $foto["id_foto"] .'"';
					echo ' height="500" ';
					echo ' /><br/>&nbsp;<br/>&nbsp;';
				}
				echo '</div>';
			}
		}
		?>
		<div align="center">
			<input type="button" value="Dados" onclick="mostraTela('dados')">
			<input type="button" value="Vistoria" onclick="mostraTela('vistoria')">
<?php if($id_ocorrencia != 0){ ?>
			<input type="button" value="Manutenção" onclick="mostraTela('manutencao')">
<?php } ?>
			<input type="button" value="Salvar" onclick="salvar_ocorrencia()">
		</div>

	</fieldset>

</form>