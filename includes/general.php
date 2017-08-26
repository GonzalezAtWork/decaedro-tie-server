<?php

/* --------------------------------------------------------------------------------------------------------- */

function exceptionHandler($exception) {
//	echo "$(document).ready(function() { alert('Exceção não tratada: ".str_replace(CHR(34), "-", $exception->getMessage())."')});";
	echo "<div class='error'>Exceção não tratada: ".$exception->getMessage()."</div>";
}

//Seta função de tratamento de exceção
set_exception_handler('exceptionHandler');


/* --------------------------------------------------------------------------------------------------------- */

function set($var, $default) {

	#Se a variável tem algum conteúdo
	if (!empty($var)) {
		#Passa o valor da própria variável
		$value = $var;
	} else {
		#Senão passa o valor default
		$value = $default;
	}

	return $value;

}

/* --------------------------------------------------------------------------------------------------------- */

function formatCPF($cpf) {
	return substr($cpf,0,3).".".substr($cpf,3,3).".".substr($cpf,6,3)."-".substr($cpf,9,2);
}

/* --------------------------------------------------------------------------------------------------------- */

function cleanCPF($cpf) {
	return str_replace(array(".", "-"), "", $cpf);
}	

/* --------------------------------------------------------------------------------------------------------- */

function formatDate($date) {
	return substr($date, 8, 2).'/'.substr($date, 5, 2).'/'.substr($date, 0, 4);
}

/* --------------------------------------------------------------------------------------------------------- */

function formatPostalCode($code) {
	return substr($code,0,5)."-".substr($code,5,3);
}

/* --------------------------------------------------------------------------------------------------------- */

function iif($condition, $trueStatement, $falseStatement) {
	if ($condition) {
		return $trueStatement;
	} else {
		return $falseStatement;
	}
}

/* --------------------------------------------------------------------------------------------------------- */

function arrow($newOrder, $oldOrder, $bDesc) {
	if ($newOrder == $oldOrder) {
		if ($bDesc == 'true') {
			$response = '<img src="images/arrow_up.png" width="13" height="9" align="abs_middle">';
		} else {
			$response = '<img src="images/arrow_down.png" width="13" height="9" align="abs_middle">';
		}
	} else {
		$response = '';
	}
	return $response;
}

/* --------------------------------------------------------------------------------------------------------- */

?>