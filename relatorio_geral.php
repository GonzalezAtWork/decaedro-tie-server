<?php

error_reporting(E_ALL | E_STRICT); 
ini_set('display_errors', true);

// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-Type: application/x-msexcel; charset=utf-8");
header ("Content-Disposition: attachment; filename=relatorio_geral.xls" );
header ("Content-Description: Kalitera SisManut" );

?><html xmlns:o="urn:schemas-microsoft-com:office:office"
  xmlns:x="urn:schemas-microsoft-com:office:excel"
  xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<style>
.num {
  mso-number-format:General;
}
.text{
  mso-number-format:"\@";/*force text*/
}
</style>
</head>
<body>
<?php
$html = "<table>";
//$html = "simak;otima;endereco;latitude;longitude;data;device;\n";
//$html = "simak;otima;endereco;tipo;latitude;longitude;\n";
$html .= "<tr>";
$html .= "<td>Data</td>";
$html .= "<td>Hora</td>";
$html .= "<td>Encarregado</td>";
$html .= "<td>Simak</td>";
$html .= "<td>Otima</td>";
$html .= "<td>Endereço</td>";
$html .= "<td>Itens</td>";
$html .= "<td>Observação</td>";
$html .= "<td>Fotos</td>";
$html .= "<td>Tipo</td>";
$html .= "</tr>";

include('classes/XMLObject.php');
include('classes/database.php');
#Conexão ao banco de dados
$db = Database::getInstance();

//$query = "select replace(replace(replace(replace(replace(obs,'Latitude:',''),'Simak:',''),'Longitude:',''),' ',';'),'.',',') || ';' || data || ';'|| ip_address || ';\n' as obs from auditoria where acao = 4 order by data desc";

$query = "";
$style = 'mso-number-format: "\@"';


$query .= " select * from ( "; 
$query .= " select ";
$query .= " dt_lastupdate, id_ocorrencia, roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos, ";
//$query .= " string_agg(vistoriasitens.nome,', ') as itens";
$query .= " vistoriasitens.nome as itens";
$query .= " from (";
$query .= " select ";
$query .= "		dt_lastupdate, ";
$query .= "		'<a target=_blank href=home.php?action=ocorrencias_edit&id_ocorrencia='|| id_ocorrencia ||'>' || id_ocorrencia::varchar || '</a>' as id_ocorrencia, ";
$query .= "		roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos,";
$query .= "		unnest( string_to_array( replace( ";
$query .= "			case when itens is null then '0' else case when itens = '' then '0' else itens end end";
$query .= "		,' ' ,'') , ',') )::smallint as itens";
$query .= " ";
$query .= "  from ";
$query .= " (";

$query .= " select ";
$query .= " 	id_ocorrencia, ";
$query .= " 	roteiro, ";
$query .= " 	tipo, ";
$query .= " 	data, ";
$query .= " 	hora, ";
$query .= " 	dt_lastupdate, ";
$query .= " 	case when tipo = 'VISTORIA' then ";
$query .= " 		case when vistoria_nome is null then ocorrencia_nome else vistoria_nome end ";
$query .= " 	else ";
$query .= " 		case when manutencao_nome is null then ocorrencia_nome else manutencao_nome end ";
$query .= " 	end as encarregado, ";
$query .= " 	codigo_abrigo as simak, ";
$query .= " 	codigo_novo as otima, ";
$query .= " 	endereco, ";
$query .= " 	case when tipo = 'VISTORIA' then ";
$query .= " 		itensvistoria ";
$query .= " 	else ";
$query .= " 		itensmanutencao ";
$query .= " 	end as itens, ";
$query .= " 	case when tipo = 'VISTORIA' then ";
$query .= " 		observacaovistoria ";
$query .= " 	else ";
$query .= " 		case when tipo = 'PUBLICIDADE' then ";
$query .= " 			observacao ";
$query .= " 		else ";
$query .= " 			observacaomanutencao ";
$query .= " 		end  ";
$query .= " 	end as observacao, ";
$query .= " 	qtd_fotos ";
$query .= " from  ";
$query .= " ( ";
$query .= " select ";
$query .= " 	ocorrencias.id_ocorrencia, ";
$query .= " 	roteiros.nome as roteiro, ";
$query .= " 	case when ocorrencias.dt_lastupdate is not null then ";
$query .= " 			ocorrencias.dt_lastupdate ";
$query .= " 		else ";
$query .= " 			ocorrencias.data ";
$query .= " 		end as dt_lastupdate, ";
$query .= " 	case when ocorrencias.dt_lastupdate is not null then ";
$query .= " 			to_char(ocorrencias.dt_lastupdate,'dd/MM/yyyy') ";
$query .= " 		else ";
$query .= " 			to_char(ocorrencias.data,'dd/MM/yyyy') ";
$query .= " 		end as data, ";
$query .= " 	case when ocorrencias.dt_lastupdate is not null then ";
$query .= " 			to_char(ocorrencias.dt_lastupdate,'HH24:MI') ";
$query .= " 		else ";
$query .= " 			to_char(ocorrencias.data,'HH24:MI') ";
$query .= " 		end as hora, ";
$query .= " 	c.nome as ocorrencia_nome,  ";
$query .= " 	usuarios.nome as vistoria_nome, ";
$query .= " 	b.nome as manutencao_nome, ";
$query .= " 	right('000000' || pontos.codigo_abrigo::text, 6) as codigo_abrigo , ";
$query .= " 	pontos.codigo_novo, ";
$query .= " 	pontos.endereco,	 ";
$query .= " 	ocorrencias.itensvistoria, ";
$query .= " 	ocorrencias.itensmanutencao, ";
$query .= " 	ocorrencias.observacao, ";
$query .= " 	ocorrencias.observacaovistoria, ";
$query .= " 	ocorrencias.observacaomanutencao, ";
$query .= " 	foto.qtd_fotos, ";
$query .= " 	gerar_os, ";
$query .= " 	case when  ";
//$query .= " 		ocorrencias.id_vistoria is not null and ";
$query .= " 		( ocorrencias.id_os is null or ocorrencias.id_os = 0 )  ";
$query .= " 	then 'VISTORIA' else  ";
$query .= " 		case when observacao = 'TROCA DE CARTAZ - PUBLICIDADE' then ";
$query .= " 			'PUBLICIDADE' ";
$query .= " 		else ";
$query .= " 			'MANUTENÇÃO'  ";
$query .= " 		end ";
$query .= " 	end as tipo ";
$query .= " from ocorrencias  ";
$query .= " 	inner join pontos on ocorrencias.id_ponto = pontos.id_ponto ";
$query .= " 	left join roteiros on roteiros.id_roteiro = pontos.id_roteiro ";
$query .= " 	left join vistoriasequipes on ocorrencias.id_vistoria = vistoriasequipes.id_vistoria ";
$query .= " 	left join usuarios on vistoriasequipes.id_equipe = usuarios.id_usuario ";
$query .= " 	left join usuarios b on ocorrencias.id_equipe = b.id_usuario ";
$query .= " 	left join usuarios c on ocorrencias.id_usuario = c.id_usuario  ";
$query .= " 	left join ( ";
$query .= " 		select id_ocorrencia, count(1) as qtd_fotos from fotografias group by id_ocorrencia  ";
$query .= " 	) as foto on foto.id_ocorrencia = ocorrencias.id_ocorrencia ";
$query .= "		where ocorrencias.gerar_os = true ";
$query .= " ) as x ";
$query .= " ) as y";
$query .= " where encarregado != 'SISTEMA'";
$query .= " ) as ocorrencias";
$query .= " left join vistoriasitens on vistoriasitens.id_item = ocorrencias.itens";
$query .= " group by vistoriasitens.nome, dt_lastupdate, id_ocorrencia, roteiro, tipo, data, hora, encarregado, simak, otima, endereco, observacao, qtd_fotos";
$query .= " ) as q";

$query .= " order by dt_lastupdate desc ";

//echo $query;

$db->setQuery($query);
$db->execute();

$dados = $db->getResultSet();

foreach ($dados as $row) {
	$html .= $row["obs"];

	$html .= "<tr>";
	$html .= "<td class=text>&#8203;". $row["data"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["hora"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["encarregado"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["simak"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["otima"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["endereco"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["itens"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["observacao"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["qtd_fotos"] ."</td>";
	$html .= "<td class=text>&#8203;". $row["tipo"] ."</td>";
	$html .= "</tr>";

}
$html .= "</table>";
echo $html;
?>
</body>
</html>