<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8'); 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();
$query  = "";
$query .= " select case when id_tipoitem = 0 then 999 else 1 end as ordem, id_tipoitem, nome from itenstipo where id_tipoitem != '1' order by ordem, nome ";
$db->setQuery($query);
$db->execute();
$itens = $db->getResultSet();

$html = '';
$contador = 0;
foreach ($itens as $row) {
	if( $contador == 0){
		$html .= '<tr>';
	}
	//if($contador == 0 && i == result.length){
	//	$html .= '<td colspan="2" width="100%">';
	//}else{
		$html .= '<td width="50%">';					
	//}
	$html .= '<a href="javascript:abreItens('. $row['id_tipoitem'] .',\''. $row['nome'] .'\')" data-role="button" style="margin:0px;">'. $row['nome'] .'</a>';
	$html .= '</td>';
	if( $contador == 1){
		$html .= '</tr>';
	}
	$contador++;
	if( $contador == 2){
		$contador = 0;
	}
}

echo $html;
?>