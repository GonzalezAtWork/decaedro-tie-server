<script type="text/javascript" src="javascript/roteiros.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="groups">
	
		<legend>Roteiros</legend>
		
		<div align="center" class="field_line" style="padding-top:10px;">
			<span class="left_label">Novo Roteiro:</span>
			<span class="login_field_container"><input type="text" name="nome" id="nome" class="medium_field" value=""></span>
			<span style="padding-left:120px;"><input type="button" name="insert" id="insert" value="Incluir"></span>
		</div>

		<div style="padding-top:20px;">
	
			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
			
				<tr>
					<td width="30">&nbsp;</td>
					<td class="table_title">Roteiro</td>
				</tr>

				<?php
					$html = "";
					
					#Conecta na base de dados
					$db = Database::getInstance();
					
					$query  = " select * from roteiros";
					$query .= " where ativo = TRUE ";
					$query .= "  order by id_roteiro;";

					$db->setQuery($query);
					$db->execute();
		
					$result = $db->getResultSet();

					foreach ($result as $row) {
						$html .= '<tr height="24" style="padding:4px">';
						$html .= '<td bgcolor="#FFFFFF" align="center">';
						$html .= '<input type="radio" name="pesquisa_id_roteiro" value="'.$row["id_roteiro"].'">';
						$html .= '</td>';
						$html .= '<td bgcolor="#FFFFFF" style="padding:4px">';
						$html .= $row["nome"];
						$html .= '</td>';
						$html .= '</tr>';
					}

					echo $html;				
				?>

			</table>

			<div align="center" style="padding-top:10px;">
				<input type="button" name="edit" id="edit" value="Editar">
				<input type="button" name="delete" id="delete" value="Excluir">
				<input type="button" name="mapa" id="mapa" value="Mapa">
			</div>

		</div>

	</fieldset>

</form>