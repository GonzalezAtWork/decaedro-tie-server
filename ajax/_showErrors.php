<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);


function exceptionHandler($exception) {
//	echo "$(document).ready(function() { alert('Exceção não tratada: ".str_replace(CHR(34), "-", $exception->getMessage())."')});";
	echo "<div class='error'>Exceção não tratada: ".$exception->getMessage()."</div>";
}

//Seta função de tratamento de exceção
set_exception_handler('exceptionHandler');
?>