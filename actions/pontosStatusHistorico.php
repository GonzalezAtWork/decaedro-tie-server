<?php

$id_ponto = $_REQUEST['id_ponto'];
$query = 'select endereco from pontos where id_ponto = ' . $id_ponto;

$db = Database::getInstance();
$db->setQuery($query);
$db->execute();
$db_result = $db->getResultAsObject();


$drop = new Dropdown();

$status = $drop->getHTMLFromQuery('select id_status as code, nome as label  from pontosStatus', '', true, 'id_status', 'style="font-size:10pt; width:150px;"');

?>

<script type="text/javascript" src="javascript/pontosStatusHistorico.js"></script>

<form name="form" id="form" method="post">

	<input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $_SESSION['id_perfil']; ?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">
	<input type="hidden" name="id_ponto" id="id_ponto" value="<?php echo $id_ponto; ?>">

	<fieldset id="groups">
	
		<legend>Histórico de Status</legend>
		
		<div align="left" class="field_line">
			<label style="width:150px">Ponto de Parada:</label>
			<span><b><label style="width:350px"><a href="home.php?action=pontos_edit&id_ponto=<?php echo $id_ponto;?>"><?php echo $db_result->endereco;?></a></label></b></span>
		</div>

		<div align="center" class="field_line" style="padding-top:10px;">
			<label style="width:100px">Novo Status:</label>
			<span><?php echo $status;?></span>
			<span><input type="button" name="insert" id="insert" value="Incluir"></span>
		</div>

		<div style="padding-top:20px;">
	
			<table cellspacing="1" cellpadding="0" width="100%" border="0" bgcolor="#005599">
			
				<tr>
					<td class="table_title" width='150'>Data</td>
					<td class="table_title" width='180'>Status</td>
					<td class="table_title">Ordem de Serviço</td>
					<td class="table_title" width='180'>Usuário</td>
				</tr>

				<?php
					$html = "";
					
					#Conecta na base de dados
					$db = Database::getInstance();

					$query  = " select a.*,u.nome as usuario, s.nome as status from pontosStatusHistorico a ";
					
					$query  .= " inner join usuarios u on a.id_usuario = u.id_usuario ";
					$query  .= " inner join pontosStatus s on a.id_status = s.id_status ";
					$query .= "  where a.id_ponto = " .  $id_ponto;
					$query .= "  order by a.data desc ";
					$query .= "  LIMIT " . $qtd_por_pagina;
					$query .= "  OFFSET " . ( $qtd_por_pagina * ($currentPage - 1));
				
					$db->setQuery($query);
					$db->execute();

					////echo $query;
		
					$result = $db->getResultSet();

					foreach ($result as $row) {
						$html .= '<tr height="24" style="padding:4px">';
						$html .= '<td bgcolor="#FFFFFF" style="padding:4px">';
						$html .= date("d/m/Y - H:i", strtotime($row["data"]));
						$html .= '</td>';
						$html .= '<td bgcolor="#FFFFFF" style="padding:4px">';
						$html .= $row["status"];
						$html .= '</td>';
						$html .= '<td bgcolor="#FFFFFF" style="padding:4px">';
						if($row["id_os"] == '0'){
							$html .= 'Entrada Manual';
						}else{
							$html .= '<a href="#">' . $row["id_os"] . '</a>';
						}
						$html .= '</td>';
						$html .= '<td bgcolor="#FFFFFF" style="padding:4px">';
						$html .= $row["usuario"];
						$html .= '</td>';
						$html .= '</tr>';
					}

					echo $html;				
				?>

			</table>
		<div align="right">
			<span>
			<?php if($currentPage > 1){ ?>
				<a href="home.php?action=pontosStatusHistorico&id_ponto=<?php echo $id_ponto;?>&pagina=<?php echo ($currentPage - 1);?>">Página Anterior</a>
			<?php } ?>
			<?php if($currentPage > 1 && $db->getRows() >= 15){ echo " | "; } ?>
			<?php if($db->getRows() >= 15){ ?>
				<a href="home.php?action=pontosStatusHistorico&id_ponto=<?php echo $id_ponto;?>&pagina=<?php echo ($currentPage + 1);?>">Próxima Página</a>
			<?php } ?>
			</span>
		</div>
			<br/>
			&nbsp;

		</div>

	</fieldset>

</form>