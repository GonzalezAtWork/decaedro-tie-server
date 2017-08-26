<?php
date_default_timezone_set('America/Sao_Paulo');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

#Inicia sessão
session_start();

#Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel; charset=utf-16");
header ("Content-Disposition: attachment; filename=".$_SESSION['filename'].".xls");
header ("Content-Description: Kalitera SisManut" );

?>
<style>
.num {mso-number-format:General;}
.text {mso-number-format:"\@";/*force text*/}
</style>
<?php
$html = '<table>'.chr(13).chr(10);
//$html .= '<thead>';
$html .= '<tr>'.chr(13).chr(10);

for ($i=0; $i < count($_SESSION['labels']); $i++) {
	$html .= '<td align="left" width="'.$_SESSION['widths'][$i].'">'.$_SESSION['labels'][$i].'</td>'.chr(13).chr(10);
}

$html .= '</tr>'.chr(13).chr(10);
//$html .= '</thead>';
//$html .= '<tbody>';

include('../classes/XMLObject.php');
include('../classes/database.php');
#Conexão ao banco de dados
$db = Database::getInstance();

$style = 'mso-number-format: "\@"';

$db->setQuery($_SESSION['query']);
$db->execute();
$dados = $db->getResultSet();

foreach ($dados as $row) {
	$html .= '<tr>'.chr(13).chr(10);
	$i = 0;
	foreach ($_SESSION['fields'] as $field) {
		$html .= '<td width="'.$_SESSION['widths'][$i++].'">'.$row[$field].'</td>'.chr(13).chr(10);
	}
	$html .= '</tr>'.chr(13).chr(10);
}
//$html .= "</tbody>";
$html .= '</table>'.chr(13).chr(10);

if (mb_detect_encoding($html) == 'UTF-8') {
	$html = mb_convert_encoding($html , "HTML-ENTITIES", "UTF-8");
}


echo $html;
?>