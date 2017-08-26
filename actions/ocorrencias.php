<?php

$db = Database::getInstance();

$drop = new Dropdown();
$equipe = $drop->getHTMLFromQuery('select id_usuario as code, nome as label from usuarios where id_perfil = 2', '' , true, 'id_equipe', 'style="font-size:10pt; width:150px;"');
$roteiro = $drop->getHTMLFromQuery('select id_roteiro as code, nome as label from roteiros', '' , true, 'id_roteiro', 'style="font-size:10pt; width:150px;"');
$gravidade = $drop->getHTMLFromQuery('select id_gravidade as code, nome as label from gravidades', '', true, 'id_gravidade', ' disabled style="font-size:10pt; width:150px;"');

$dia_semana = array("Domingo", "Segunda", "Terça" , "Quarta" , "Quinta", "Sexta", "Sábado");
$data_atual = date("d/m/Y");

	$query = "";
	$query .= " select ";
	$query .= " pontos.*, ocorrencias.*, roteiros.nome as roteiro, usuarios.nome as equipe, ";

	$query .= " case when nomeimagenspublicidade is not null then 'Publicidade' else ";
	$query .= "		case when criticos.critico > 0 then 'Critico' else ";
	$query .= "			case when urgentes.urgente > 0 then 'Urgente' else ";
	$query .= "				'Normal' ";
	$query .= "			end ";
	$query .= "		end ";
	$query .= " end as tipo, ";

	$query .= " case when nomeimagenspublicidade is not null then 1 else ";
	$query .= "		case when criticos.critico > 0 then 2 else ";
	$query .= "			case when urgentes.urgente > 0 then 3 else ";
	$query .= "				4 ";
	$query .= "			end ";
	$query .= "		end ";
	$query .= " end as ordem, ";

	$query .= " qtd_fotos ";
	$query .= " from ocorrencias ";
	$query .= " left join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
	$query .= " left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
	$query .= " left join vistorias on vistorias.id_vistoria = ocorrencias.id_vistoria ";
	$query .= " left join usuarios on ocorrencias.id_equipe = usuarios.id_usuario ";


	$query .= " left join ( ";
	$query .= "		select id_ocorrencia, count(1) as urgente from vistoriasitens ";
	$query .= "			inner join ( ";
	$query .= "				select id_ocorrencia, unnest(string_to_array(replace(replace(ocorrencias.itensvistoria,' ', ''),',',', '),', '))::int as id_item from ocorrencias ";
	$query .= "			) as ocorrencias on vistoriasitens.id_item = ocorrencias.id_item ";
	$query .= "		where vistoriasitens.urgente = true ";
	$query .= "		group by id_ocorrencia ";
	$query .= "	) as urgentes on ocorrencias.id_ocorrencia = urgentes.id_ocorrencia";

	$query .= " left join ( ";
	$query .= "		select id_ocorrencia, count(1) as critico from vistoriasitens ";
	$query .= "			inner join ( ";
	$query .= "				select id_ocorrencia, unnest(string_to_array(replace(replace(ocorrencias.itensvistoria,' ', ''),',',', '),', '))::int as id_item from ocorrencias ";
	$query .= "			) as ocorrencias on vistoriasitens.id_item = ocorrencias.id_item ";
	$query .= "		where vistoriasitens.critico = true ";
	$query .= "		group by id_ocorrencia ";
	$query .= "	) as criticos on ocorrencias.id_ocorrencia = criticos.id_ocorrencia";

	$query .= " left join ( ";
	$query .= "		select id_ocorrencia, count(1) as qtd_fotos from fotografias ";
	$query .= "		group by id_ocorrencia ";
	$query .= "	) as fotos on ocorrencias.id_ocorrencia = fotos.id_ocorrencia";

	$query .= " where ";
	$query .= " id_os is null and ";	
	$query .= " ocorrencias.executada = false and gerar_os = true and ( ( ocorrencias.id_vistoria is not null and vistorias.executada = true ) or ( ocorrencias.id_vistoria is null ) ) ";	
	$query .= " order by ordem, ocorrencias.data ";

	//echo $query;

	$db->setQuery($query);
	$db->execute();

	$result = $db->getResultSet();

	$total_pontos = $db->getRows();

?>

<script type="text/javascript" src="javascript/ocorrencias.js"></script>

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
	<fieldset id="groups">
	<legend>Ocorrências em Aberto</legend>

<div style="padding-top:20px;">

			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
<tr>
	<td class="table_title" width="30px">&nbsp;</td>
	<td class="table_title" width="50px"><b>Ocorrência</b></td>
	<td class="table_title" width="70px"><b>Data</b></td>
	<td class="table_title" width="50px"><b>Simak</b></td>
	<td class="table_title"><b>Endere&ccedil;o</b></td>
	<td class="table_title" width="50px"><b>Tipo</b></td>
	<td class="table_title"><b>Equipe</b></td>
	<td class="table_title"><b>Roteiro</b></td>
	<td class="table_title"><b>Fotos</b></td>
</tr>

<?php
	if($total_pontos == 0){
		$html = "";
		$html .= '<tr height="24" style="padding:4px">';
		$html .= '<td class="table_cell" align="center" colspan="100">';
		$html .= 'Nenhuma ocorrência encontrada';
		$html .= '</td>';
		$html .= '</tr>';
		echo $html;
	}

	foreach ($result as $row) {
		//$itensVistoria = explode(',',$row['itensvistoria']);
		$itensVistoria = $row['itensvistoria'];
?>
<tr height="24" style="padding:4px">
	<td bgcolor="#FFFFFF" align="center"><input type="radio" name="pesquisa_id_ocorrencia" value="<?php echo $row["id_ocorrencia"];?>"></td>
	<td bgcolor="#FFFFFF" align="center"><?php echo $row["id_ocorrencia"];?></td>
	<td bgcolor="#FFFFFF" align="center"><?php echo date("d/m/Y", strtotime($row["data"]));?></td>
	<td bgcolor="#FFFFFF" align="center"><?php echo $row['codigo_abrigo'];?></td>
	<td bgcolor="#FFFFFF">&nbsp;<?php echo $row['endereco'];?></td>
	<td bgcolor="#FFFFFF" align="center"><?php echo $row['tipo'];?></td>
	<td bgcolor="#FFFFFF" width="250px" valign="middle">&nbsp;<?php echo $row['equipe'];?></td>
	<td bgcolor="#FFFFFF" width="150px">&nbsp;<?php echo $row['roteiro'];?></td>
	<td bgcolor="#FFFFFF" width="30px" align="center">&nbsp;<?php echo $row['qtd_fotos'];?></td>
</tr>
<?php
	}
?>
</table>
</div>
	<div align="center" style="padding-top:10px;">
		<input type="button" name="edit" id="edit" value="Visualizar">
	</div>
<br/>
	</fieldset>

</form>