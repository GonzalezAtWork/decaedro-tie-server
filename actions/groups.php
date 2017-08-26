<script type="text/javascript" src="javascript/groups.js"></script>

<form name="groups" id="groups" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="groups">
	
		<legend>Grupos</legend>
		
		<div align="center" class="field_line" style="padding-top:10px;">
			<span class="left_label">Nome do grupo:</span>
			<span class="login_field_container"><input type="text" name="group_name" id="group_name" class="medium_field" value=""></span>
			<span style="padding-left:120px;"><input type="button" name="insert" id="insert" value="Incluir"></span>
		</div>
	
		<div style="padding-top:20px;">
	
			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
			
				<tr>
					<td>&nbsp;</td>
					<td class="table_title">Nome do Grupo</td>
				</tr>

				<?php
					$html = "";
					
					#Conecta na base de dados
					$db = Database::getInstance();
					
					$query = "select group_id, group_name from groups where id_perfil = ".$_SESSION['id_perfil']." order by group_name;";

					$db->setQuery($query);
					$db->execute();
		
					$result = $db->getResultSet();

					foreach ($result as $row) {
						$html .= '<tr height="24" style="padding:4px">';
						$html .= '<td bgcolor="#FFFFFF" align="center">';
						$html .= '<input type="checkbox" name="group_id[]" value="'.$row["group_id"].'">';
						$html .= '</td>';
						$html .= '<td bgcolor="#FFFFFF" style="padding:4px">';
						$html .= $row["group_name"];
						$html .= '</td></tr>';
					}

					echo $html;				
				?>

			</table>

			<div align="center" style="padding-top:10px;">
				<input type="button" name="delete" id="delete" value="Excluir">
			</div>

		</div>

	</fieldset>

</form>