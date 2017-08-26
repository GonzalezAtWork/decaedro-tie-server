<?php

$id_os = $_REQUEST['id_os'];

$db = Database::getInstance();

$query  = " select oss.*, eq.equipe, rt.roteiro, vis.qtd_pontos from oss ";
/*
$query .= " left join ( ";
$query .= "		select id_os, string_agg(nome,', ') as equipe from ossEquipes ";
$query .= "			inner join equipes on equipes.id_equipe = ossEquipes.id_equipe ";
$query .= "		group by id_os ";
$query .= " ) as eq on oss.id_os = eq.id_os";
*/

$query .= " left join ( ";
$query .= "		select id_os, string_agg(nome,', ') as equipe from ossEquipes ";
$query .= "			inner join usuarios on usuarios.id_usuario = ossEquipes.id_equipe ";
$query .= "		group by id_os ";
$query .= " ) as eq on oss.id_os = eq.id_os";

$query .= " left join ( ";
$query .= "		select id_os, string_agg(nome,', ') as roteiro from ossRoteiros ";
$query .= "			inner join roteiros on roteiros.id_roteiro = ossRoteiros.id_roteiro ";
$query .= "		group by id_os ";
$query .= " ) as rt on oss.id_os = rt.id_os";

$query .= " left join ( ";
$query .= "		select id_os, sum(qtd_pontos) as qtd_pontos from ossRoteiros group by id_os ";
$query .= " ) as vis on oss.id_os = vis.id_os";

$query .= " where oss.id_os = " . $id_os;

//echo $query;

$db->setQuery($query);
$db->execute();

$retorno= $db->getResultSet();

if(is_array($retorno)){
	$retorno = $retorno[0];
}

$drop = new Dropdown();
$equipe = $drop->getHTMLFromQuery('select id_usuario as code, nome as label from usuarios where id_perfil = 2', '' , true, 'id_equipe', 'style="font-size:10pt; width:150px;"');
$roteiro = $drop->getHTMLFromQuery('select id_roteiro as code, nome as label from roteiros', '' , true, 'id_roteiro', 'style="font-size:10pt; width:150px;"');
$gravidade = $drop->getHTMLFromQuery('select id_gravidade as code, nome as label from gravidades', $retorno['id_gravidade'], true, 'id_gravidade', ' disabled class="tiny_field"');

if($retorno['agendada'] == 't' || $retorno['executada'] == 't'){
	$disabled = ' disabled ';
}else{
	$disabled = '';
}

$id_prioridade = $retorno['id_prioridade'];

$agendada = $retorno['agendada'];
$executada = $retorno['executada'];

$dia_semana = array("Domingo", "Segunda", "Terça" , "Quarta" , "Quinta", "Sexta", "Sábado");
$data_atual = date("d/m/Y");

	$query = "";
	$query .= " select pontos.*, ocorrencias.*, roteiros.nome as roteiro, vistorias.data, usuarios.nome as equipe, foto.total, ocorrencias.dt_lastupdate from ocorrencias ";
	$query .= " left join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
	$query .= " left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
	$query .= " left join vistorias on vistorias.id_vistoria = ocorrencias.id_vistoria ";
	$query .= " left join usuarios on ocorrencias.id_equipe = usuarios.id_usuario ";
	$query .= " left join (select id_ocorrencia, count(1) as total from fotografias group by id_ocorrencia ) as foto on foto.id_ocorrencia = ocorrencias.id_ocorrencia ";
	$query .= " where ";
	if($id_os != '0'){
		if($agendada == 't'){
			$query .= " ( id_os = " . $id_os . ") and ";
		}else{
			$query .= " ( id_os = " . $id_os . " or (id_os is null and ocorrencias.executada = false )) and ";
		}
	}else{
		$query .= " id_os is null and ocorrencias.executada = false and ";	
	}
	
	$query .= " gerar_os = true and ( ( ocorrencias.id_vistoria is not null and vistorias.executada = true ) or ( ocorrencias.id_vistoria is null ) ) ";	
	if($id_prioridade == 3){
		$query .= " and ocorrencias.nomeimagenspublicidade is not null ";	
	}else{
		$query .= " and ocorrencias.nomeimagenspublicidade is null ";		
	}
	$query .= " order by ocorrencias.executada desc, ocorrencias.dt_lastupdate desc, ocorrencias.posicao";
  
	//echo $query; 

	$db->setQuery($query);
	$db->execute();

	$result = $db->getResultSet();

	$total_pontos = $db->getRows();

?>

<script type="text/javascript" src="javascript/oss.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <script>
  strLancaEquipe  = "";
  strLancaEquipe += '<fieldset id="groups" style="width:300px">';
  strLancaEquipe += '<div class="line"><label style="width:100px">Equipe:</label><span class="field"> <?php echo $equipe;?></span></div>';
  strLancaEquipe += '<div class="line"><label style="width:100px">Roteiro:</label><span class="field"> <?php echo $roteiro;?></span></div>';
  strLancaEquipe += '<div class="line"><label style="width:100px">Quantidade:</label><span class="field"> <input type="text" style="width:50px" name="qtd" id="qtd"></span></div>';
  strLancaEquipe += '<div align="center"><span><input onclick="LancaEquipes()" type="button" value="Lançar"></span></div>';
  strLancaEquipe += '</fieldset>';

  strTrocaEquipe  = "";
  strTrocaEquipe += '<fieldset id="groups" style="width:300px">';
  strTrocaEquipe += '<div class="line"><label style="width:100px">Ocorrência:</label><span class="field"><input disabled width="60" style="width:60px" type="text" name="tr_id_ocorrencia" id="tr_id_ocorrencia" value="**tr_id_ocorrencia**"></span></div>';
  strTrocaEquipe += '<div class="line"><label style="width:100px">Simak:</label><span class="field"><input disabled width="60" style="width:60px" type="text" name="tr_codigo" id="tr_codigo" value="**tr_codigo**"></span></div>';
  strTrocaEquipe += '<div class="line"><label style="width:100px">Ponto:</label><span class="field"><input disabled  width="190" style="width:190px" type="text" name="tr_nome" id="tr_nome" value="**tr_nome**"></span></div>';
  strTrocaEquipe += '<div class="line"><label style="width:100px">Roteiro:</label><span class="field"><input disabled type="text" name="tr_roteiro" id="tr_roteiro" value="**tr_roteiro**"></span></div>';
  strTrocaEquipe += '<div class="line"><label style="width:100px">Equipe:</label><span class="field"><?php echo $equipe;?></span></div>';
  strTrocaEquipe += '<div align="center"><span><input onclick="execTrocaEquipe()" type="button" value="Trocar"></span></div>';
  strTrocaEquipe += '</fieldset>';
  strTrocaEquipe = strTrocaEquipe.split('id_equipe').join('tr_id_equipe');
  /*
  $(function() {
    $( "#data" ).datepicker({
      showOn: "button",
      buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
      buttonImageOnly: true,
	  dateFormat: "yy-mm-dd"
    });
  });
  */
  </script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_os" id="id_os" value="<?php echo $id_os;?>">

	<fieldset id="groups">
		<legend>Ordem de Serviço<?php if( $id_prioridade == 1) {echo ' - URGENTE';}?><?php if( $id_prioridade == 3) {echo ' - PUBLICIDADE';}?></legend>

		<div style="width:50%; float:left;">
			<label>Código:</label>
			<input type="text" class="tiny_field" style="width:250px;" value="<?php echo substr('000000' . $retorno['id_os'], -5);?>" disabled>

			<label>Data:</label>
			<input type="text" name="data" id="data" class="tiny_field" style="width:250px;" disabled value="<?php echo $retorno['data'];?>">

			<label>Executada:</label>
			<input type="checkbox" name="executada" id="executada" <?php echo ( $executada == 't' )?' selected checked ':'';?>/>

		</div>

		<div style="width:50%; float:left;">
			<label>Previsão de Chuva:</label>
			<select name="chuva" id="chuva" disabled class="tiny_field">
				<option value="f" <?php if( $retorno['chuva'] == 'f' ){echo 'selected';}?>>Não</option>
				<option value="t" <?php if( $retorno['chuva'] == 't' ){echo 'selected';}?>>Sim</option>
			</select>
			<label>Prioridade:</label>
			<select name="id_prioridade" id="id_prioridade" disabled class="tiny_field">
				<option value="2" <?php if( $retorno['id_prioridade'] == 2 ){echo 'selected';}?>>Normal</option>
				<option value="1" <?php if( $retorno['id_prioridade'] == 1 ){echo 'selected';}?>>Urgente</option>
				<option value="3" <?php if( $retorno['id_prioridade'] == 3 ){echo 'selected';}?>>Publicidade</option>
			</select>
			<label>Referência:</label>
			<span class="field"><?php echo $gravidade;?></span>
		</div>

		<div>

			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
				<thead>
					<tr>
					<?php if ($agendada != 't'){ ?>
						<th><b>Incluir</b></td>
					<?php } ?>
						<th><b>Exec.</b></td>
						<th><b>Ocorrência</b></td>
						<th><b>Simak</b></td>
						<th><b>Endere&ccedil;o</b></td>
						<th><b>Equipe</b></td>
						<th><b>Roteiro</b></td>
						<th><b>Fotos</b></td>
						<th><b>Hora</b></td>
					</tr>
				</thead>

				<?php

				if($total_pontos == 0) {
					?>
					<tr><td class="table_cell" align="center" colspan="100">Nenhuma ocorrência encontrada.</td></tr>
					<?php
				} else {
					foreach ($result as $row) {
						//$itensVistoria = explode(',',$row['itensvistoria']);
						$itensVistoria = $row['itensvistoria'];
						?>
						<tr height="24">
					<?php if ($agendada != 't'){ ?>
							<td bgcolor="#FFFFFF" align="center"><input <?php echo $disabled;?> <?php echo ($row['id_os'] == $id_os)?' selected checked ':'';?> type="checkbox" name="ocorrencias[]" id="ocorrencias[]" value="<?php echo $row['id_ocorrencia'];?>"/></td>
					<?php }else{ ?>
							<input type="hidden" name="ocorrencias[]" id="ocorrencias[]" value="<?php echo $row['id_ocorrencia'];?>">
					<?php } ?>

							<td bgcolor="#FFFFFF" align="center"><input disabled <?php echo ($row['executada'] =='t')?' selected checked ':'';?> type="checkbox" /></td>
							<td bgcolor="#FFFFFF" align="center"><a href="home.php?action=ocorrencias_edit&id_ocorrencia=<?php echo $row['id_ocorrencia'];?>&id_os=<?php echo $row['id_os'];?>"><?php echo $row['id_ocorrencia'];?></a></td>
							<td bgcolor="#FFFFFF" align="center"><?php echo $row['codigo_abrigo'];?></td>
							<td bgcolor="#FFFFFF">&nbsp;<?php echo $row['endereco'];?></td>
							<td bgcolor="#FFFFFF" width="250px" valign="middle">&nbsp;<?php if($disabled == ""){?><img title="Alterar Equipe" onclick="trocaEquipe('<?php echo $row['id_ocorrencia'];?>','<?php echo $row['codigo_abrigo'];?>','<?php echo $row['endereco'];?>','<?php echo $row['roteiro'];?>','<?php echo $row['id_equipe'];?>')" src="http://png.findicons.com/files/icons/1676/primo/128/exchange.png" width="20" height="20"><?php }?> <?php echo $row['equipe'];?></td>
							<td bgcolor="#FFFFFF" width="150px">&nbsp;<?php echo $row['roteiro'];?></td>
							<td bgcolor="#FFFFFF" width="20px">&nbsp;<?php echo $row['total'];?></td>

							<td bgcolor="#FFFFFF" width="20px">&nbsp;<?php if($row["executada"] == 't') echo date("H:i", strtotime($row["dt_lastupdate"]));?></td>


						</tr>
					<?php
					}

				}
				?>
			</table>

		</div>

		<div align="center" style="padding-top:10px;">
			<?php
			if($agendada != 't' && $executada != 't'){
				?>
				<span><input type="button" name="save" id="save" value="Salvar"></span>
				<span><input type="button" name="lancar_equipes" id="lancar_equipes" value="Lançar Equipes"></span>
				<span><input type="button" name="schedule" id="schedule" value="Agendar OS"></span>
				<?php
			}

			if($agendada == 't' && $executada != 't'){
				?>
				<span><input type="button" name="cria_guia" id="cria_guia" value="Emitir Guia de Ordem de Serviço"></span>
				<!--
				<span><input type="button" name="exec" id="exec" value="Executar"></span>
				-->
				<?php
			}

			if( $executada == 't'){
				?>
				<!-- <span><input type="button" name="exec" id="exec" value="Visualizar Dados"></span> -->
				<?php
			}
			?>
		</div>

	</fieldset>

</form>