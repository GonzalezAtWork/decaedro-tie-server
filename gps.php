<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=gps.csv");
header("Pragma: no-cache");
header("Expires: 0");

//$html = "simak;otima;endereco;latitude;longitude;data;device;\n";
$html = "simak;otima;endereco;tipo;latitude;longitude;\n";

include('classes/XMLObject.php');
include('classes/database.php');
#Conexão ao banco de dados
$db = Database::getInstance();

//$query = "select replace(replace(replace(replace(replace(obs,'Latitude:',''),'Simak:',''),'Longitude:',''),' ',';'),'.',',') || ';' || data || ';'|| ip_address || ';\n' as obs from auditoria where acao = 4 order by data desc";

$query = "";
$query .= " select codigo_abrigo ||';'|| codigo_novo ||';'|| endereco ||';'|| pontostipo.nome ||';'|| replace(gmaps_latitude,'.',',') ||';'|| replace(gmaps_longitude,'.',',') || ';\n' as obs ";
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

echo $html;
?>