<script type="text/javascript" src="javascript/pontosStatus.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="medium">
	
		<legend>Status de Pontos de Parada</legend>
		
		<!-- inicio do filtro -->
		<?php 
		$drop = new Dropdown();
		$select = $drop->getHTMLFromArray(array("Status"), $filterField, 'filterfield', FALSE);
		?>
		<div id="filtercontainer"">
			<span id="filterlabel"><?php echo $select;?></span>
			<span><input type="text" name="filtertext" id="filtertext" class="medium_field" value="<?php echo $filterText;?>"></span>
			<span><input type="button" name="filterbutton" id="filterbutton" value="Filtrar"></span>
		</div>
		<!-- final do filtro -->

		<table cellspacing="1" cellpadding="0" width="100%" border="0">
		
			<thead>
				<tr>
					<th class="tinyfield">&nbsp;</th>
					<th>Status</th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				$grid = "";
				
				#Conecta na base de dados
				$db = Database::getInstance();
				
				$query  = "select id_status, nome from pontosStatus where ativo = TRUE ";
            
            if (!empty($filterText)) {
               $query .= " and nome ilike '%".$filterText."%'";
            }
            $query .= " order by 2;";
            

				$db->setQuery($query);
				$db->execute();
	
				$result = $db->getResultSet();

				foreach ($result as $row) {
					$grid .= '<tr>';
					$grid .= '<td align="center"><input type="radio" name="pesquisa_id_status" value="'.$row["id_status"].'"></td>';
					$grid .= '<td>'.$row["nome"].'</td>';
					$grid .= '</tr>';
				}

				echo $grid;				
				?>
			</tbody>


		</table>

			<div id="buttons">
				<input type="button" name="edit" id="edit" value="Editar">
				<input type="button" name="insert" id="insert" value="Incluir">
				<input type="button" name="delete" id="delete" value="Excluir">
			</div>

		</div>

	</fieldset>

</form>