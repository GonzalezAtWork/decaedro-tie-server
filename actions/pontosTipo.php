<script type="text/javascript" src="javascript/pontosTipo.js"></script>

<form name="pontosTipo" id="pontosTipo" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

	<fieldset id="medium">
	
		<legend>Tipos de Pontos de Parada</legend>
					
      <?php
      $aFilterLabels = array('Tipo');
      include("includes/filter.php");
      ?>

		<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
		
         <thead>
            <tr>
               <th class="tinyfield">&nbsp;</td>
               <th>Nome</td>
               <th class="tinyfield">Totem</td>
            </tr>
         </thead>
         
         <tbody>
			<?php
				$html = "";
				
				#Conecta na base de dados
				$db = Database::getInstance();
				
				$query  = "select id_tipo, nome, totem from pontosTipo where ativo = TRUE ";
            if (!empty($filterText)) {
               $query .= " and nome ilike '%".$filterText."%'";
            }
            $query .= " order by 2;";

              
				$db->setQuery($query);
				$db->execute();
	
				$result = $db->getResultSet();

				foreach ($result as $row) {
					$html .= '<tr>';
					$html .= '<td align="center"><input type="radio" name="pesquisa_id_tipo" value="'.$row["id_tipo"].'"></td>';
					$html .= '<td>'.$row["nome"].'</td>';
					$html .= '<td align="center">'.( $row["totem"] != 'f' ? '<img src="images/ok.png" width="14" height="14">' : '&nbsp;' ).'</td>';
					$html .= '</tr>';
				}

				echo $html;				
			?>
         </tbody>
		</table>

		<div align="center" style="padding-top:10px;">
			<input type="button" name="edit" id="edit" value="Editar">
			<input type="button" name="delete" id="delete" value="Excluir">
		</div>

	</fieldset>

</form>