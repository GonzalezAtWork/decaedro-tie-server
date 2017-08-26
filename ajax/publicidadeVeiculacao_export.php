<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

function exceptionHandler($exception) {
	echo "<div class='error'".$exception->getMessage()."</div>";
}

//Seta função de tratamento de exceção
set_exception_handler('exceptionHandler');

include('../classes/XMLObject.php');
include('../classes/database.php');

#Constantes do insert na tabela de ocorrências
$toInsertHeader = "insert into ocorrencias (id_ponto, gerar_os, executada, vistoriada, observacao, id_equipe, itensVistoria, observacaoVistoria, itensManutencao, observacaoManutencao, nomeImagensPublicidade) values ";
$toInsert = "";

#Conexão ao banco de dados
$db = Database::getInstance();

#Buscando itens de vistoria de publicidade
$query  = "select id_item, nome from vistoriasitens where id_tipoitem = 1;";
$db->setQuery($query);
$db->execute();
$result = $db->getResultSet();

#Preenchendo array com itens de vistoria de publicidade
$aItensVistoria = array();
$aNomeItensVistoria = array();

foreach ($result as $row) {
	array_push($aItensVistoria, $row["id_item"]);
	array_push($aNomeItensVistoria, substr(strtolower($row["nome"]), 0, 14));
}

#Setando variáveis de referência pela primeira vez
$old = array("ano" => "", "semana" => "", "simak" => "");

#Buscando dados da publicidade
$query  = " select distinct p.id_ponto, v.simak, v.ano, v.semana, v.caixa, v.face, aux.nome_imagem as nome_imagem_atual, v.nome_imagem as nome_imagem_nova ";
$query .= " from publicidadeveiculacao v ";

$query .= " left join publicidadeveiculacao aux on (";
$query .= "		aux.simak = v.simak and ";
$query .= "		aux.caixa = v.caixa and ";
$query .= "		aux.face = v.face and ";
$query .= "		aux.semana = (case when (v.semana - 1) = 0 then 52 else (v.semana - 1) end) and ";
$query .= "		aux.ano = (case when (v.semana - 1) = 0 then (v.ano-1) else v.ano end) and";
$query .= "		aux.ativo = false and ";
$query .= "		aux.id_veiculacao in ( ";
$query .= "			select id_veiculacao from (";
$query .= "				select max(id_veiculacao) as id_veiculacao, simak";
$query .= "				from publicidadeveiculacao";
$query .= "				where ativo = false";
$query .= "				group by simak, caixa, face";
$query .= "			) ";
$query .= "			as t) ";
$query .= " ) ";

$query .= " left join pontos p on (v.simak = p.codigo_abrigo) ";
$query .= " where v.ativo = true ";
$query .= " order by  v.ano, v.semana, v.simak, v.caixa, v.face desc;";

$db->setQuery($query);
$db->execute();
$result = $db->getResultSet();

#Carregando dados da publicidade
foreach ($result as $row) {

	#Buscando dados da semana, caixa e face
	$key = array_search(strtolower("caixa ".$row["caixa"]." face ".$row["face"]), $aNomeItensVistoria);

	#Se não foi encontrada caixa e face correspondente na tabela de itens de vistoria
	if ($key === false) {

		die("Erro: Não há item de vistoria correspondente à combinação de caixa e face do abrigo: ".strtolower("caixa ".chr(34).$row["caixa"].chr(34).", face ".chr(34).$row["face"]).chr(34).".");

	} else {

		#Buscando itens de vistoria e imagens
		$itens = $aItensVistoria[$key];
		$imagens = $aItensVistoria[$key].",".$row["nome_imagem_atual"].";".$row["nome_imagem_nova"];

		if (!($row["ano"] == $old["ano"] && $row["semana"] == $old["semana"] && $row["simak"] == $old["simak"])) {
			#Se o ano, semana e simak atual são diferentes dos anteriores, significa que mudou o abrigo

			#Atualiza dados de referência
			$old['ano'] = $row["ano"];
			$old['semana'] = $row["semana"];
			$old['simak'] = $row["simak"];

			#Constrói o array com os dados a serem inseridos
			$line = array( "id_ponto"  => $row["id_ponto"],
								"itens"     => $itens,
								"imagens"   => $imagens);

		} else {

			#Adiciona item de vistoria e imagens ao índice atual do array
			$line["itens"]   .= ",".$itens;
			$line["imagens"] .= "|".$imagens;

			$toInsert .= "(".$line["id_ponto"].", ";					   //id_ponto
			$toInsert .= "true, ";										   //gerar_os
			$toInsert .= "false, ";										   //executada
			$toInsert .= "true, ";										   //vistoriada
			$toInsert .= "'TROCA DE CARTAZ - PUBLICIDADE', ";			   //observacao
			$toInsert .= "null, ";										   //id_equipe
//			$toInsert .= "'".$line["itens"]."', ";						   //itensVistoria
			$toInsert .= "'". str_replace(',',', ',$line["itens"]) ."', "; //itensVistoria - SEPARADO POR ', '
			$toInsert .= "'', ";										   //observacaoVistoria
			$toInsert .= "'', ";										   //itensManutencao
			$toInsert .= "'', ";										   //observacaoManutencao
			$toInsert .= "'".$line["imagens"]."'), ";					   //nomeImagens

		}

	}

}

#Concatena todas as partes do insert e por fim atualiza a tabela publicidadeVeiculacao
$query = $toInsertHeader.substr($toInsert, 0, -2)."; update publicidadeveiculacao set ativo = false;";

$db->setQuery($query);
$db->execute();

echo json_encode(true);
?>