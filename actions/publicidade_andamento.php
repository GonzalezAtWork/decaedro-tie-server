<script type="text/javascript" src="javascript/vistorias.js"></script>
<form name="publicidade" id="publicidade" method="post">
	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">
	<fieldset id="groups">
		<legend>Resumo das Trocas de Cartazes</legend>
		<div style="padding-top:20px;">
			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
				<?php
					$html = "";
					
					#Conecta na base de dados
					$db = Database::getInstance();
					
					$query   = " ";
					$query  .= "	select oss.id_os, oss.executada, oss.data, oss.hs_saida, oss.hs_chegada, eq.equipe, rt.roteiro, vis.qtd_pontos, a.total, b.tot_ocorrencia, c.tot_fotos from oss ";
					$query  .= "	left join ( ";
					$query  .= "			select count(1) as total, id_os from ocorrencias where vistoriada = true group by id_os ";
					$query  .= "		) as a on a.id_os = oss.id_os ";
					$query  .= "	left join ( ";
					$query  .= "			select count(1) as tot_ocorrencia, id_os from ocorrencias where vistoriada = true and gerar_os = true group by id_os ";
					$query  .= "		) as b on b.id_os = oss.id_os ";
					$query  .= "	left join ( ";
					$query  .= "			select count(1) as tot_fotos, id_os from (select count(1), id_ocorrencia, id_os from fotografias group by id_ocorrencia, id_os) as x group by id_os  ";
					$query  .= "		) as c on c.id_os = oss.id_os ";
					$query  .= "	left join (  ";
					$query  .= "			select id_os, string_agg(nome,', ') as equipe from ossEquipes  ";
					$query  .= "			inner join usuarios on usuarios.id_usuario = ossEquipes.id_equipe  ";
					$query  .= "			group by id_os  ";
					$query  .= "		) as eq on oss.id_os = eq.id_os ";
					$query  .= "	left join (  ";
					$query  .= "			select id_os, string_agg(nome,', ') as roteiro from ossRoteiros  ";
					$query  .= "			inner join roteiros on roteiros.id_roteiro = ossRoteiros.id_roteiro  ";
					$query  .= "			group by id_os  ";
					$query  .= "		) as rt on oss.id_os = rt.id_os ";
					$query  .= "	left join (  ";
					$query  .= "			select id_os, sum(qtd_pontos) as qtd_pontos from ossRoteiros group by id_os  ";
					$query  .= "		) as vis on oss.id_os = vis.id_os ";
					$query  .= "	where ";
					$query  .= "	oss.agendada = true ";
					$query  .= "	order by oss.data desc ";

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
						$html .= '<b><u>'.$row["id_os"].' - '.$row["roteiro"].' - '.$row["equipe"].' | Total '.$row["qtd_pontos"].' Abrigos</u></b><br/>';
						if($row["executada"] == 'f'){
							$html .= '<b>Em Andamento - Data: '.$row["data"].' - Ultima Atualização: '.$row["hs_saida"].'</b><br/>';
						}else{
							$html .= '<b>Finalizada - Data: '.$row["data"].' - Ultima Atualização: '.$row["hs_chegada"].'</b><br/>';						
						}
						$html .= '&nbsp;<br/>';
						$html .= '<b>'.$row["total"].' abrigos foram efetuados</b><br/>';
						$html .= ''.$row["tot_ocorrencia"].' ocorrências observadas<br/>';
						$html .= ''.$row["tot_fotos"].' ocorrências fotografadas<br/>';
						$html .= '&nbsp;<br/>';
						$html .= '<a target="_blank" href="http://tie4.decaedro.net/home.php?action=oss_edit&id_os='.$row["id_os"].'">Ver informações/fotos</a>';
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