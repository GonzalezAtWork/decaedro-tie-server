<?php

$data_inicial = (isset($_REQUEST['data_inicial']))?$_REQUEST['data_inicial']: date('Y-m-d', strtotime(date("Y-m-d") . ' - 2 days'));
$data_final = (isset($_REQUEST['data_final']))?$_REQUEST['data_final']: date('Y-m-d', strtotime(date("Y-m-d") . ' + 1 days'));

$nome_usuario = (isset($_REQUEST['nome_usuario']))?$_REQUEST['nome_usuario']:"";

$drop = new Dropdown();
$encarregados = $drop->getHTMLFromQuery(' select nome as code, nome as label from usuarios where id_perfil = 2 order by nome ', $nome_usuario, true, 'nome_usuario');

?>
<script type="text/javascript" src="javascript/vistorias.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <script>
  $(function() {
    $( "#data_final" ).datepicker({
      showOn: "button",
      buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
      buttonImageOnly: true,
	  dateFormat: "yy-mm-dd"
    });
    $( "#data_inicial" ).datepicker({
      showOn: "button",
      buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
      buttonImageOnly: true,
	  dateFormat: "yy-mm-dd"
    });
  });
  </script>
<form name="vistorias" id="vistorias" method="post" action="home.php?action=vistorias_andamento">
	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">
	<fieldset id="groups">
		<legend>Resumo das Vistorias</legend>

		<div align="center" style="padding-top:10px;">
			<label style="width:20px">Início:</label>
				<span class="field"><input type="text" name="data_inicial" id="data_inicial" class="small_field" style="width:100px;" value="<?php echo $data_inicial;?>"></span>
			<label style="width:20px">Fim:</label>
				<span class="field"><input type="text" name="data_final" id="data_final" class="small_field" style="width:100px;" value="<?php echo $data_final;?>"></span>
			<label style="width:50px">Encarregados:</label>
				<span class="field"><?php echo $encarregados; ?></span>
			<input type="button" onclick="form.submit()" value="Atualizar"/>
		</div>

		<div style="padding-top:20px;">
			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
				<?php
					$html = "";
					
					#Conecta na base de dados
					$db = Database::getInstance();
					
					$query   = " ";
					$query  .= "	select vistorias.id_vistoria, vistorias.executada, vistorias.data, vistorias.hs_saida, vistorias.hs_chegada, eq.equipe, rt.roteiro, vis.qtd_pontos, a.total, b.tot_ocorrencia, c.tot_fotos from vistorias ";
					$query  .= "	left join ( ";
					$query  .= "			select count(1) as total, id_vistoria from ocorrencias where vistoriada = true group by id_vistoria ";
					$query  .= "		) as a on a.id_vistoria = vistorias.id_vistoria ";
					$query  .= "	left join ( ";
					$query  .= "			select count(1) as tot_ocorrencia, id_vistoria from ocorrencias where vistoriada = true and gerar_os = true group by id_vistoria ";
					$query  .= "		) as b on b.id_vistoria = vistorias.id_vistoria ";
					$query  .= "	left join ( ";
					$query  .= "			select count(1) as tot_fotos, id_vistoria from (select count(1), id_ocorrencia, id_vistoria from fotografias group by id_ocorrencia, id_vistoria) as x group by id_vistoria  ";
					$query  .= "		) as c on c.id_vistoria = vistorias.id_vistoria ";
					$query  .= "	left join (  ";
					$query  .= "			select id_vistoria, string_agg(nome,', ') as equipe from vistoriasEquipes  ";
					$query  .= "			inner join usuarios on usuarios.id_usuario = vistoriasEquipes.id_equipe  ";
					$query  .= "			group by id_vistoria  ";
					$query  .= "		) as eq on vistorias.id_vistoria = eq.id_vistoria ";
					$query  .= "	left join (  ";
					$query  .= "			select id_vistoria, string_agg(nome,', ') as roteiro from vistoriasRoteiros  ";
					$query  .= "			inner join roteiros on roteiros.id_roteiro = vistoriasRoteiros.id_roteiro  ";
					$query  .= "			group by id_vistoria  ";
					$query  .= "		) as rt on vistorias.id_vistoria = rt.id_vistoria ";
					$query  .= "	left join (  ";
					$query  .= "			select id_vistoria, sum(qtd_pontos) as qtd_pontos from vistoriasRoteiros group by id_vistoria  ";
					$query  .= "		) as vis on vistorias.id_vistoria = vis.id_vistoria ";
					$query  .= "	where ";
					$query  .= "	vistorias.agendada = true ";

					$query  .= " and vistorias.data >= ('". date("Y-m-d", strtotime( $data_inicial ) ) ."')::date ";
					$query  .= " and vistorias.data <= ('". date("Y-m-d", strtotime( $data_final ) ) ."')::date ";

					if($nome_usuario != ""){
						$query  .= " and eq.equipe ilike '%" . $nome_usuario . "%' ";
					}

					$query  .= "	order by vistorias.id_vistoria desc ";

					//echo $query;
					$db->setQuery($query);
					$db->execute();
		
					$result = $db->getResultSet();
					if($db->getRows() == 0){
						$html .= '<tr height="24" style="padding:4px">';
						$html .= '<td class="table_cell" align="center" colspan="100">';
						$html .= 'Nenhuma vistoria em andamento.';
						$html .= '</td>';
						$html .= '</tr>';
					}
					foreach ($result as $row) {
						$html .= '<tr height="24" style="padding:4px">';
						$html .= '<td class="table_cell">';
						$html .= '<b><u>'.$row["id_vistoria"].' - '.$row["roteiro"].' - '.$row["equipe"].'</u></b><br/>';
						if($row["executada"] == 'f'){
							$html .= '<b>Em Andamento - Data: '.$row["data"].' - Ultima Atualização: '.$row["hs_saida"].'</b><br/>';
						}else{
							$html .= '<b>Finalizada - Data: '.$row["data"].' - Ultima Atualização: '.$row["hs_chegada"].'</b><br/>';						
						}
						$html .= '&nbsp;<br/>';
						$html .= '<b>Foram agendados '.$row["qtd_pontos"].' abrigos</b><br/>';
                  $html .= ''.$row["total"].' abrigos foram vistoriados<br/>';
						$html .= ''.$row["tot_ocorrencia"].' ocorrências observadas<br/>';
						$html .= ''.$row["tot_fotos"].' ocorrências fotografadas<br/>';
						$html .= '&nbsp;<br/>';
						$html .= '<a target="_blank" href="http://tie4.decaedro.net/home.php?action=vistoriasGuiaPreenche&id_vistoria='.$row["id_vistoria"].'">Ver informações/fotos</a>';

						$html .= '</td>';
						$html .= '</tr>';
					}
					echo $html;				
				?>
			</table>
			<!--
			<div align="center" style="padding-top:10px;">
				<input type="button" name="edit" id="edit" value="Visualizar">
			</div>
			-->
		</div>
	</fieldset>
</form>