<?php
include("includes/head.php");
?>

<form action="home.php" method="post">
	<fieldset style="width:640px;">
		<legend>Dados do usuário</legend>
		<div style="padding:12px 0px;">
			<div class="field_line">
				<span class="left_label">CPF:</span>
				<span class="field_container"><input type="text" name="" id="" class="field"/></span>
			</div>
			<div class="field_line">
				<span class="left_label">Nome:</span>
				<span class="field_container"><input type="text" name="" id="" class="field"/></span>
			</div>
			<div class="field_line">
				<span class="left_label">Grupo:</span>
				<span class="field_container"><input type="text" name="" id="" class="field"/></span>
			</div>
			<div class="field_line">
				<span class="left_label">Ramal:</span>
				<span class="field_container"><input type="text" name="" id="" class="field"/></span>
			</div>
			<div class="field_line">
				<span class="left_label">Celular:</span>
				<span class="field_container"><input type="text" name="" id="" class="field"/></span>
			</div>
		</div>
		<div align="center" style="padding:12px 0px;">
			<span><input type="submit" name="salvar" id="salvar" value="Salvar"></span>
			<span><input type="button" name="cancelar" id="cancelar" value="Cancelar"></span>
		</div>
	</fieldset>
</form>


<?php
include("includes/tail.php");
?>