<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

#Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=excel.xls" );
header ("Content-Description: Kalitera SisManut" );
?>
<style>
	.num {
	  mso-number-format:General;
	}
	.text {
	  mso-number-format:"\@";/*force text*/
	}
</style>
<?php
$html = "<table>";
//$html = "simak;otima;endereco;latitude;longitude;data;device;\n";
//$html = "simak;otima;endereco;tipo;latitude;longitude;\n";
$html .= "<tr>";
$html .= "<td>simak</td>";
$html .= "<td>otima</td>";
$html .= "<td>endereco</td>";
$html .= "<td>tipo</td>";
$html .= "<td>latitude</td>";
$html .= "<td>longitude</td>";
$html .= "</tr>";

include('classes/XMLObject.php');
include('classes/database.php');
#Conexão ao banco de dados
$db = Database::getInstance();

//$query = "select replace(replace(replace(replace(replace(obs,'Latitude:',''),'Simak:',''),'Longitude:',''),' ',';'),'.',',') || ';' || data || ';'|| ip_address || ';\n' as obs from auditoria where acao = 4 order by data desc";

$style = 'mso-number-format: "\@"';
$query = "";
$query .= " select '<tr><td class=text>&#8203;' || codigo_abrigo ||'</td><td class=text>&#8203;'|| codigo_novo ||'</td><td class=text>&#8203;'|| endereco ||'</td><td class=text>&#8203;'|| pontostipo.nome ||'</td><td class=text>&#8203;'|| gmaps_latitude ||'</td><td class=text>&#8203;'|| gmaps_longitude || '</td></tr>' as obs ";
$query .= " from pontos ";
$query .= " inner join pontospadrao on pontospadrao.id_padrao = pontos.id_padrao ";
$query .= " inner join pontostipo on pontostipo.id_tipo = pontospadrao.id_tipo ";
$query .= " where char_length(gmaps_latitude) > 10 ";

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

foreach ($dados as $row) {
	$html .= $row["obs"];
}
$html .= "</table>";
echo $html;
?>