<script type="text/javascript" src="javascript/oss.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="groups">
	
		<legend>Ordens de Serviço</legend>
	
	
		<div style="padding-top:20px;">
	
			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
			
				<tr>
					<td width="30">&nbsp;</td>
					<td width="30"  class="table_title">Código</td>
					<td width="30"  class="table_title">Agend.</td>
					<td width="30"  class="table_title">Exec.</td>
					<td             class="table_title">Data</td>
					<td width="60"  class="table_title">Prioridade</td>
					<td width="280" class="table_title">Equipes</td>
					<td width="280" class="table_title">Roteiros</td>
					<td width="30"  class="table_title">Qtdd</td>
				</tr>

				<?php

					$prioridades = array('','Urgente', 'Normal', 'Publicidade');
					$html = "";
					
					#Conecta na base de dados
					$db = Database::getInstance();
					
					$query  = " select oss.*, eq.equipe, rt.roteiro, vis.qtd_pontos from oss ";

					$query .= " left join ( ";
					$query .= "		select id_os, string_agg(nome,', ') as equipe from ( ";
					$query .= "			select id_os, id_equipe from ocorrencias where id_os is not null group by id_equipe,id_os ";
					$query .= "		) as ocor  ";
					$query .= "		inner join usuarios on usuarios.id_usuario = ocor.id_equipe  ";
					$query .= "		group by id_os  ";
					$query .= " ) as eq on oss.id_os = eq.id_os";

					$query .= " left join ( ";
					$query .= "		select id_os, string_agg(nome,', ') as roteiro from (";
					$query .= "			select id_os, id_roteiro from ocorrencias ";
					$query .= "			inner join pontos on pontos.id_ponto = ocorrencias.id_ponto";
					$query .= "			where id_os is not null group by id_roteiro ,id_os";
					$query .= "		) as rot ";
					$query .= "		inner join roteiros on roteiros.id_roteiro = rot.id_roteiro ";
					$query .= "		group by id_os ";
					$query .= " ) as rt on oss.id_os = rt.id_os";

					$query .= " left join ( ";
					$query .= "		select id_os, sum(1) as qtd_pontos from ocorrencias group by id_os ";
					$query .= " ) as vis on oss.id_os = vis.id_os";
					$query .= " where oss.ativo = true";
					$query .= " order by oss.data desc, oss.id_os desc ";

					////echo $query;
					$db->setQuery($query);
					$db->execute();
		
					$result = $db->getResultSet();
					if($db->getRows() == 0){
						$html .= '<tr height="24" style="padding:4px">';
						$html .= '<td class="table_cell" align="center" colspan="100">';
						$html .= 'Nenhum registro encontrado';
						$html .= '</td>';
						$html .= '</tr>';
					}
					foreach ($result as $row) {
						$html .= '<tr height="24" style="padding:4px">';
						$html .= '<td class="table_cell" align="center">';
						$html .= '<input type="radio" name="pesquisa_id_os" value="'.$row["id_os"].'">';
						$html .= '</td>';
						
						$html .= '<td class="table_cell" align="center">';
						$html .= substr('000000' . $row["id_os"], -5);
						$html .= '</td>';
						$html .= '<td class="table_cell" align="center">';
						$html .= '<input type="checkbox" disabled';
						if($row["agendada"] == 't'){
							$html .= ' selected checked ';
						}
						$html .= ' >';
						$html .= '</td>';
						$html .= '<td class="table_cell" align="center">';
						$html .= '<input type="checkbox" disabled';
						if($row["executada"] == 't'){
							$html .= ' selected checked ';
						}
						$html .= ' >';
						$html .= '</td>';
						$html .= '<td class="table_cell">'. date("d/m/Y", strtotime($row["data"])) .'</td>';
						$html .= '<td class="table_cell" align="center">'.$prioridades[$row["id_prioridade"]].'</td>';
						$html .= '<td class="table_cell">'.$row["equipe"].'</td>';
						$html .= '<td class="table_cell">'.$row["roteiro"].'</td>';
						$html .= '<td class="table_cell" align="center">'.$row["qtd_pontos"].'</td>';
						$html .= '</tr>';
					}

					echo $html;				
				?>

			</table>
			<div align="center" style="padding-top:10px;">
				<input type="button" name="edit" id="edit" value="Visualizar">
				<input type="button" name="new" id="new" value="Nova OS">
				<input type="button" name="critic" id="critic" value="Nova OS Urgente">
				<input type="button" name="publicidade" id="publicidade" value="Nova OS Publicidade">
			</div>

		</div>

	</fieldset>

</form>