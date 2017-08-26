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
$query .= " select ";
$query .= "		case when itensTipo.id_tipoitem = 0 then 9 else itensTipo.id_tipoitem end as ordenacao, ";
$query .= "		vistoriasItens.*, itensTipo.id_tipoitem as tipo_id, itensTipo.nome as tipo_nome  from vistoriasItens ";
$query .= " inner join itensTipo on vistoriasItens.id_tipoitem = itensTipo.id_tipoitem ";
$query .= " where itensTipo.id_tipoitem not in (0, 1) ";
$query .= " order by ordenacao ";
$db->setQuery($query);
$db->execute();
$itens = $db->getResultSet();

$html = '';

$contador = 0;
$tipo_id = "";
$tipo_nome = "";

foreach ($itens as $row) {
	if($tipo_id != $row["tipo_id"]){
		$tipo_id = $row["tipo_id"];
		$tipo_nome = $row["tipo_nome"];
		if($contador != 0) {
			$html .= '</div>';
			$html .= '</fieldset>';
			$html .= '</div>';
		}
		$html .= '<div data-role="fieldcontain" id="titulo_tipo'. $tipo_id .'" name="titulo_tipo'. $tipo_id .'">';
		$html .= '<fieldset data-role="controlgroup" data-type="vertical">';
		$html .= '<legend>'. $tipo_nome .':</legend>';
		$html .= '<div id="tipo'. $tipo_id .'" name="tipo'. $tipo_id .'">';
		$contador++;
	}
	$html .= '<input ';
	$html .= '	name="iv_'. $row["id_item"] .'" ';
	$html .= '	id="iv_'. $row["id_item"] .'" ';
	$html .= '	obrigafoto="'. $row["foto"] .'" ';
	$html .= '	onclick="chkFoto(\''. $row["id_item"] .'\')"  ';
	$html .= '	data-theme="c" ';
	$html .= '	type="checkbox">';
	$html .= '<label for="iv_'. $row["id_item"] .'">';
	$html .= '<small>&nbsp;<br/></small>'. $row["nome"] .'<small><br/>&nbsp;</small>';
	$html .= '</label>';
}
$html .= '</div>';
$html .= '</fieldset>';
$html .= '</div>';

echo $html;
?>