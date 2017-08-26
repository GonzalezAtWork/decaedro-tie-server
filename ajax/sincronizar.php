<?php

ob_start("ob_gzhandler"); 
header( 'Access-Control-Allow-Origin: *' );
//header( 'Content-type: application/json; charset=UTF-8' );
header( 'Content-Encoding: gzip' );
header( 'cache-control: must-revalidate' );
header('Content-Type: text/html; charset=utf-8'); 

function anti_sql_injection($str) {
    if (!is_numeric($str)) {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        //$str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
    }
    return $str;
}

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();

$id_usuario = anti_sql_injection( (isset($_REQUEST['id_usuario']))?$_REQUEST['id_usuario']:"" );
$token = anti_sql_injection( (isset($_REQUEST['token']))?$_REQUEST['token']:"" );

if($id_usuario == "" && $token != ""){
	$query  = " select * from mobile_login where token = '". $token ."' ";
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	foreach ($dados as $row) {
		$id_usuario = $row["id_usuario"];
	}
}

if($id_usuario == ""){
	$id_usuario = 0;
}

$device = (isset($_REQUEST['device']))?$_REQUEST['device']:"";
if($device == ""){
	$device = $_SERVER["REMOTE_ADDR"];
}

$query = "insert into auditoria(ip_address,id_usuario,acao,obs ) values('". $device ."','". $id_usuario ."','1','Sincronizou Mobile');";
$db->setQuery($query);
$db->execute();

$tabelas = array();
$inserts = array();

$createTables  = "";
$createTables .= " select ";
$createTables .= "		'CREATE TABLE IF NOT EXISTS ***tabela***(' || string_agg(colunas_com_tipo , ', ') || ')' as createtable, ";
$createTables .= "		string_agg(colunas , '||,||') as colunas, ";
$createTables .= "		'***tabela***' as tabela";
$createTables .= " from ( ";
$createTables .= "		select ";
$createTables .= "			column_name || ' ' || data_type || ";
$createTables .= "				case substring( column_default from 0 for 8 )  when 'nextval' then ' UNIQUE ' else '' end ";
$createTables .= "				as colunas_com_tipo, ";
$createTables .= "			column_name as colunas ";
$createTables .= "		from information_schema.columns where table_name='***tabela***' ";
$createTables .= " ) as tabela ";



// ******************* USUARIOS - INICIO *******************

$TabelaUsuarios = 'usuarios_'. date('U');

$query  = " select ";
$query .= "		ativo, logado_mobile, celular, ddd, email, nome, cpf, id_perfil, id_usuario, '". $token."' as token  ";
$query .= " into ". $TabelaUsuarios;
$query .= "		from usuarios  ";
$query .= "		 where id_usuario = '". $id_usuario ."' ";

$db->setQuery($query);
$db->execute();

// ******************* USUARIOS - FIM *******************

// ******************* FOTOGRAFIAS - INICIO *******************

$TabelaFotografias = 'fotografias_'. date('U');

$query  = " select ";
$query .= "				*, false as uploaded  ";
$query .= " into ". $TabelaFotografias;
$query .= "		from fotografias  ";
$query .= "		 where 1 = 0 ";

$db->setQuery($query);
$db->execute();

// ******************* FOTOGRAFIAS - FIM *******************

// ******************* PUBLICIDADEIMAGENS - INICIO *******************

$TabelaPublicidadeImagens = 'publicidadeimagens_'. date('U');

$query  = " select ";
$query .= "		publicidadeimagens.nome as id_publicidadeimagem_unique,  ";
$query .= "		*  ";
$query .= " into ". $TabelaPublicidadeImagens;
$query .= "		from publicidadeimagens  ";
$query .= "		 where publicidadeimagens.nome in ( ";
$query .= "			select distinct ";
$query .= "			unnest( ";
$query .= "				string_to_array( ";
$query .= "					trim( both ';' from  ";
$query .= "						replace(  ";
$query .= "							replace(  ";
$query .= "								replace(  ";
$query .= "									replace(  ";
$query .= "										replace(  ";
$query .= "											string_agg( '|' || nomeimagenspublicidade, ';')  ";
$query .= "										, '|53,', ';' ) ";
$query .= "									, '|52,', ';' ) ";
$query .= "								, '|51,', ';' ) ";
$query .= "							, '|50,', ';' )  ";
$query .= "						, ';;', ';' )  ";
$query .= "					) ";
$query .= "				,';') ";
$query .= "			) as nome ";
$query .= "			from ocorrencias where ocorrencias.executada = false and ocorrencias.id_equipe = ". $id_usuario ." and nomeimagenspublicidade is not null ";
$query .= "		 ) ";

$db->setQuery($query);
$db->execute();

// ******************* FOTOGRAFIAS - FIM *******************


// ******************* PUBLICIDADE - INICIO *******************

$TabelaPublicidades = 'publicidades_'. date('U');

$query  = " select ";
$query .= "				oss.id_os as id_os_unique,  ";
$query .= "				oss.andamento, ";
$query .= "				oss.id_carro, ";
$query .= "				oss.km_saida, ";
$query .= "				oss.km_chegada, ";
$query .= "				oss.km_rodados, ";
$query .= "				oss.hs_saida, ";
$query .= "				oss.hs_chegada, ";
$query .= "				oss.hs_rodados, ";
$query .= "				oss.agendada,  ";
$query .= "				oss.executada,  ";
$query .= "				gravidades.nome as referencia, ";
$query .= "				right('00000' || oss.id_os::text, 5) as id_formatado,  ";
$query .= "				to_char(oss.data,'dd/MM/yyyy') as data_formatada,  ";
$query .= "				eq.equipe, rt.roteiro, vis.qtd_pontos  ";
$query .= " into ". $TabelaPublicidades;
$query .= "		from oss  ";
$query .= "		 left join gravidades  on oss.id_gravidade = gravidades.id_gravidade ";
$query .= "		 left join (  ";
$query .= "				select id_os, string_agg(nome,', ') as equipe from (  ";
$query .= "					select id_os, id_equipe from ocorrencias where id_os is not null group by id_equipe,id_os  ";
$query .= "				) as ocor   ";
$query .= "				inner join usuarios on usuarios.id_usuario = ocor.id_equipe   ";
$query .= "				group by id_os   ";
$query .= "		 ) as eq on oss.id_os = eq.id_os ";
$query .= "		 ";
$query .= "		 left join (  ";
$query .= "				select id_os, string_agg(nome,', ') as roteiro from ( ";
$query .= "					select id_os, id_roteiro from ocorrencias  ";
$query .= "					inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
$query .= "					where id_os is not null group by id_roteiro ,id_os ";
$query .= "				) as rot  ";
$query .= "				inner join roteiros on roteiros.id_roteiro = rot.id_roteiro  ";
$query .= "				group by id_os  ";
$query .= "		 ) as rt on oss.id_os = rt.id_os ";
$query .= "		 ";
$query .= "		 left join (  ";
$query .= "				select id_os, sum(1) as qtd_pontos from ocorrencias group by id_os  ";
$query .= "		 ) as vis on oss.id_os = vis.id_os ";
$query .= "		 where oss.ativo = true ";
$query .= "		 and oss.id_os in ( select id_os from ocorrencias where id_os is not null and ocorrencias.id_equipe = ". $id_usuario ." and nomeimagenspublicidade is not null) ";
$query .= "		and oss.agendada = true ";
// Não traz os que já executou!
$query .= "		and oss.executada = false ";
$query .= "		 order by oss.data desc, oss.id_os desc  ";

$db->setQuery($query);
$db->execute();

$TabelaPublicidadesPontos = 'publicidadespontos_'. date('U');

$query = " select ";
//$query .= " 	 ocorrencias.*, pontos.*, roteiros.nome as roteiro, vistorias.data, usuarios.nome as equipe ";
$query .= "		ocorrencias.id_ocorrencia as id_ocorrencia_unique, ";
$query .= "		posicao, ";
$query .= "		ocorrencias.id_vistoria,  ";
$query .= "		ocorrencias.id_os,  ";
$query .= "		ocorrencias.executada,  ";
$query .= "		pontos.id_ponto,  ";
$query .= "		pontos.codigo_abrigo as simak,  ";
$query .= "		pontos.codigo_novo as otima,  ";
$query .= "		endereco,  ";
$query .= "		gmaps_latitude,  ";
$query .= "		gmaps_longitude,   ";
$query .= "		pontosTipo.id_tipo, ";
$query .= "		pontosTipo.nome as tipo,  ";
$query .= "		pontosTipo.cor as tipo_cor,  ";
$query .= "		roteiros.id_roteiro,  ";
$query .= "		roteiros.nome as roteiro,  ";
$query .= "		roteiros.cor as roteiro_cor,  ";
$query .= "		ocorrencias.observacao,  ";
$query .= "		ocorrencias.itensVistoria,  ";
$query .= "		ocorrencias.itensManutencao,  ";
$query .= "		ocorrencias.observacaoManutencao,  ";
$query .= "		ocorrencias.fotoManutencao,  ";
$query .= "		ocorrencias.dt_lastupdate,  ";
// Nome da Imagem - UNIQUE!!!
$query .= "		ocorrencias.nomeimagenspublicidade  ";

$query .= " into ". $TabelaPublicidadesPontos;
$query .= " 	 from ocorrencias ";
$query .= " 	 left join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
$query .= " 	 left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
$query .= " 	 left join vistorias on vistorias.id_vistoria = ocorrencias.id_vistoria ";
$query .= " 	 left join usuarios on ocorrencias.id_equipe = usuarios.id_usuario ";
$query .= "		left join bairros on pontos.id_bairro = bairros.id_bairro ";
$query .= "		left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
$query .= "		left join pontosTipo on pontosTipo.id_tipo = pontosPadrao.id_tipo ";

// Não traz os que já executou!
$query .= "		inner join oss on oss.id_os = ocorrencias.id_os and oss.ativo = true and oss.executada = false ";

$query .= " 	 where ";
$query .= " 	oss.agendada = true and ";
$query .= " 	ocorrencias.executada = false and	";
$query .= " 	 nomeimagenspublicidade is not null and "; // tras somente as de publicidade
$query .= " 	 gerar_os = true and ( ( ocorrencias.id_vistoria is not null and vistorias.executada = true ) or ( ocorrencias.id_vistoria is null ) ) 	";
$query .= " 	 and id_equipe = ". $id_usuario ." ";
$query .= " 	 order by id_os, ocorrencias.posicao ";

$db->setQuery($query);
$db->execute();

// ******************* PUBLICIDADE - FIM *******************

// ******************* MANUTENCAO - INICIO *******************

$TabelaManutencoes = 'manutencao_'. date('U');

$query  = " select ";
$query .= "				oss.id_os as id_os_unique,  ";
$query .= "				oss.andamento, ";
$query .= "				oss.id_carro, ";
$query .= "				oss.km_saida, ";
$query .= "				oss.km_chegada, ";
$query .= "				oss.km_rodados, ";
$query .= "				oss.hs_saida, ";
$query .= "				oss.hs_chegada, ";
$query .= "				oss.hs_rodados, ";
$query .= "				oss.agendada,  ";
$query .= "				oss.executada,  ";
$query .= "				gravidades.nome as referencia, ";
$query .= "				right('00000' || oss.id_os::text, 5) as id_formatado,  ";
$query .= "				to_char(oss.data,'dd/MM/yyyy') as data_formatada,  ";
$query .= "				eq.equipe, rt.roteiro, vis.qtd_pontos  ";
$query .= " into ". $TabelaManutencoes;
$query .= "		from oss  ";
$query .= "		 left join gravidades  on oss.id_gravidade = gravidades.id_gravidade ";
$query .= "		 left join (  ";
$query .= "				select id_os, string_agg(nome,', ') as equipe from (  ";
$query .= "					select id_os, id_equipe from ocorrencias where id_os is not null group by id_equipe,id_os  ";
$query .= "				) as ocor   ";
$query .= "				inner join usuarios on usuarios.id_usuario = ocor.id_equipe   ";
$query .= "				group by id_os   ";
$query .= "		 ) as eq on oss.id_os = eq.id_os ";
$query .= "		 ";
$query .= "		 left join (  ";
$query .= "				select id_os, string_agg(nome,', ') as roteiro from ( ";
$query .= "					select id_os, id_roteiro from ocorrencias  ";
$query .= "					inner join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
$query .= "					where id_os is not null group by id_roteiro ,id_os ";
$query .= "				) as rot  ";
$query .= "				inner join roteiros on roteiros.id_roteiro = rot.id_roteiro  ";
$query .= "				group by id_os  ";
$query .= "		 ) as rt on oss.id_os = rt.id_os ";
$query .= "		 ";
$query .= "		 left join (  ";
$query .= "				select id_os, sum(1) as qtd_pontos from ocorrencias group by id_os  ";
$query .= "		 ) as vis on oss.id_os = vis.id_os ";
$query .= "		 where oss.ativo = true ";
$query .= "		 and oss.id_os in ( select id_os from ocorrencias where id_os is not null and ocorrencias.id_equipe = ". $id_usuario ." and nomeimagenspublicidade is null) ";
$query .= "		and oss.agendada = true ";
// Não traz os que já executou!
$query .= "		and oss.executada = false ";
$query .= "		 order by oss.data desc, oss.id_os desc  ";

$db->setQuery($query);
$db->execute();

$TabelaManutencoesPontos = 'manutencoespontos_'. date('U');

$query = " select ";
//$query .= " 	 ocorrencias.*, pontos.*, roteiros.nome as roteiro, vistorias.data, usuarios.nome as equipe ";
$query .= "		ocorrencias.id_ocorrencia as id_ocorrencia_unique, ";
$query .= "		posicao, ";
$query .= "		ocorrencias.id_vistoria,  ";
$query .= "		ocorrencias.id_os,  ";
$query .= "		ocorrencias.executada,  ";
$query .= "		pontos.id_ponto,  ";
$query .= "		pontos.codigo_abrigo as simak,  ";
$query .= "		pontos.codigo_novo as otima,  ";
$query .= "		endereco,  ";
$query .= "		gmaps_latitude,  ";
$query .= "		gmaps_longitude,   ";
$query .= "		pontosTipo.id_tipo, ";
$query .= "		pontosTipo.nome as tipo,  ";
$query .= "		pontosTipo.cor as tipo_cor,  ";
$query .= "		roteiros.id_roteiro,  ";
$query .= "		roteiros.nome as roteiro,  ";
$query .= "		roteiros.cor as roteiro_cor,  ";
$query .= "		ocorrencias.observacao,  ";
$query .= "		ocorrencias.itensVistoria,  ";
$query .= "		ocorrencias.itensManutencao,  ";
$query .= "		ocorrencias.observacaoManutencao,  ";
$query .= "		ocorrencias.dt_lastupdate,  ";
$query .= "		ocorrencias.fotoManutencao  ";
$query .= " into ". $TabelaManutencoesPontos;
$query .= " 	 from ocorrencias ";
$query .= " 	 left join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
$query .= " 	 left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
$query .= " 	 left join vistorias on vistorias.id_vistoria = ocorrencias.id_vistoria ";
$query .= " 	 left join usuarios on ocorrencias.id_equipe = usuarios.id_usuario ";
$query .= "		left join bairros on pontos.id_bairro = bairros.id_bairro ";
$query .= "		left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
$query .= "		left join pontosTipo on pontosTipo.id_tipo = pontosPadrao.id_tipo ";

// Não traz os que já executou!
$query .= "		inner join oss on oss.id_os = ocorrencias.id_os and oss.ativo = true and oss.executada = false ";

$query .= " 	 where ";
$query .= " 	oss.agendada = true and ";
$query .= " 	ocorrencias.executada = false and	";
$query .= " 	 nomeimagenspublicidade is null and "; // não tras as de publicidade
$query .= " 	 gerar_os = true and ( ( ocorrencias.id_vistoria is not null and vistorias.executada = true ) or ( ocorrencias.id_vistoria is null ) ) 	";
$query .= " 	 and id_equipe = ". $id_usuario ." ";
$query .= " 	 order by id_os, ocorrencias.posicao ";

$db->setQuery($query);
$db->execute();


// ******************* MANUTENCAO - FIM *******************

// ******************* VISTORIAS - INICIO *******************

$TabelaVistorias = 'vistorias_'. date('U');

$query  = " select ";
$query .= "		vistorias.id_vistoria as id_vistoria_unique, ";
$query .= "		vistorias.andamento, ";
$query .= "		vistorias.agendada, ";
$query .= "		vistorias.executada, ";
$query .= "		vistorias.id_carro, ";
$query .= "		vistorias.km_saida, ";
$query .= "		vistorias.km_chegada, ";
$query .= "		vistorias.km_rodados, ";
$query .= "		vistorias.hs_saida, ";
$query .= "		vistorias.hs_chegada, ";
$query .= "		vistorias.hs_rodados, ";
$query .= "		gravidades.nome as referencia, ";
$query .= "		case vistorias.periodo when 'D' then 'Diurno' else 'Noturno' end as periodo, ";
$query .= "		right('00000' || vistorias.id_vistoria::text, 5) as id_formatado, ";
$query .= "		to_char(vistorias.data,'dd/MM/yyyy') as data_formatada, ";
$query .= "		eq.equipe, rt.roteiro, vis.qtd_pontos ";
$query .= " into ". $TabelaVistorias;
$query .= " from vistorias ";
$query .= "		left join gravidades  on vistorias.id_gravidade = gravidades.id_gravidade ";
$query .= " left join ( ";
$query .= "		select id_vistoria, string_agg(nome,', ') as equipe from vistoriasEquipes ";
$query .= "			inner join usuarios on usuarios.id_usuario = vistoriasEquipes.id_equipe ";
$query .= "		group by id_vistoria ";
$query .= " ) as eq on vistorias.id_vistoria = eq.id_vistoria";
$query .= " left join ( ";
$query .= "		select id_vistoria, string_agg(nome,', ') as roteiro from vistoriasRoteiros ";
$query .= "			inner join roteiros on roteiros.id_roteiro = vistoriasRoteiros.id_roteiro ";
$query .= "		group by id_vistoria ";
$query .= " ) as rt on vistorias.id_vistoria = rt.id_vistoria";
$query .= " left join ( ";
$query .= "		select id_vistoria, sum(qtd_pontos) as qtd_pontos from vistoriasRoteiros group by id_vistoria ";
$query .= " ) as vis on vistorias.id_vistoria = vis.id_vistoria";
$query .= " where ";
$query .= "		vistorias.ativo = true ";
$query .= "		and vistorias.id_vistoria in ( select id_vistoria from vistoriasEquipes where vistoriasEquipes.id_equipe = ". $id_usuario ." ) ";

// Não traz os que já executou!
$query .= " 	and vistorias.agendada = true ";
$query .= "		and vistorias.executada = false ";

$query .= " order by vistorias.data desc ";
$db->setQuery($query);
$db->execute();

$TabelaVistoriasPontos = 'vistoriaspontos_'. date('U');

$query = " select ";
$query .= "		ocorrencias.id_ocorrencia as id_ocorrencia_unique, ";
$query .= "		posicao, ";
$query .= "		ocorrencias.gerar_os, ";
$query .= "		ocorrencias.id_vistoria,  ";
$query .= "		ocorrencias.vistoriada,  ";
$query .= "		pontos.id_ponto,  ";
$query .= "		pontos.codigo_abrigo as simak,  ";
$query .= "		pontos.codigo_novo as otima,  ";
$query .= "		endereco,  ";
$query .= "		gmaps_latitude,  ";
$query .= "		gmaps_longitude,   ";
$query .= "		pontosTipo.id_tipo, ";
$query .= "		pontosTipo.nome as tipo,  ";
$query .= "		pontosTipo.cor as tipo_cor,  ";
$query .= "		roteiros.id_roteiro,  ";
$query .= "		roteiros.nome as roteiro,  ";
$query .= "		roteiros.cor as roteiro_cor,  ";
$query .= "		ocorrencias.observacao,  ";
$query .= "		ocorrencias.itensVistoria,  ";
$query .= "		ocorrencias.observacaoVistoria,  ";
$query .= "		ocorrencias.dt_lastupdate,  ";
$query .= "		ocorrencias.fotoVistoria  ";
$query .= " into ". $TabelaVistoriasPontos;
$query .= " from pontos  ";
$query .= "		left join bairros on pontos.id_bairro = bairros.id_bairro ";
$query .= "		left join pontosPadrao on pontos.id_padrao = pontosPadrao.id_padrao ";
$query .= "		left join pontosTipo on pontosTipo.id_tipo = pontosPadrao.id_tipo ";
$query .= "		left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
$query .= "		inner join ocorrencias on ocorrencias.id_ponto = pontos.id_ponto  ";
// Não traz os que já executou!
$query .= "		inner join vistorias on vistorias.id_vistoria = ocorrencias.id_vistoria and vistorias.ativo = true and vistorias.executada = false ";

$query .= " where ";
$query .= "		ocorrencias.id_vistoria in ( select id_vistoria from vistoriasEquipes where vistoriasEquipes.id_equipe = ". $id_usuario ." ) ";

// Não traz os que já executou!

$query .= " 	and vistorias.agendada = true ";
$query .= "		and ocorrencias.executada = false and vistoriada = false and gerar_os = false ";

$query .= " order by ocorrencias.posicao  ";
$db->setQuery($query);
$db->execute();

// ******************* VISTORIAS - FIM *******************

$arrTabelas = array($TabelaPublicidades, $TabelaPublicidadesPontos, $TabelaManutencoes, $TabelaManutencoesPontos, $TabelaVistorias, $TabelaVistoriasPontos, $TabelaUsuarios, 'vistoriasitens','carros', 'itenstipo', $TabelaFotografias, $TabelaPublicidadeImagens);

foreach ($arrTabelas as $arrTabela) {
	$query = str_replace('***tabela***', $arrTabela, $createTables);
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	foreach ($dados as $row) {
		$tabela = '"'. $row["createtable"] .'"';
		$insert  = " select ";
		$insert .= " 'insert into ". $row["tabela"] ." (". str_replace('||','', $row["colunas"]) .") values(***' || COALESCE(";
		$insert .= str_replace("||,||","::text,'') ||'***,***'|| COALESCE(", $row["colunas"]);
		$insert .= "::text,'') || '***)' as createinsert ";
		$insert .= " from ". $row["tabela"] ." ";		
		array_push($inserts, $insert);
		array_push($tabelas, $tabela);
	}
}
$retorno = "";
$retorno .= "[<br>";
foreach ($tabelas as $tabela) {
	$retorno .= $tabela . ',<br>';
}
foreach ($inserts as $insert) {

	$query = $insert;
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	foreach ($dados as $row) {
		$retorno .= '"'. str_replace("_unique","", $row["createinsert"]) .'",<br>';
	}
}

$retorno .= '""]';
$retorno = str_replace("***","'", $retorno);
$retorno = str_replace("_unique smallint"," smallint UNIQUE ", $retorno);
$retorno = str_replace("_unique character"," character UNIQUE ", $retorno);
$retorno = str_replace("DEFAULT 'nextval"," UNIQUE DEFAULT 'nextval", $retorno);
//$retorno = str_replace('"",<br>','', $retorno);
$retorno = str_replace(',<br>""]','<br>]', $retorno);
$retorno = str_replace(' without time zone','', $retorno);
$retorno = str_replace(' varying','', $retorno);

$retorno = str_replace($TabelaFotografias,'fotografias', $retorno);
$retorno = str_replace($TabelaPublicidadeImagens,'publicidadeimagens', $retorno);
$retorno = str_replace($TabelaUsuarios,'usuarios', $retorno);
$retorno = str_replace($TabelaManutencoes,'manutencoes', $retorno);
$retorno = str_replace($TabelaManutencoesPontos,'manutencoespontos', $retorno);
$retorno = str_replace($TabelaVistorias,'vistorias', $retorno);
$retorno = str_replace($TabelaVistoriasPontos,'vistoriaspontos', $retorno);
$retorno = str_replace($TabelaPublicidades,'publicidades', $retorno);
$retorno = str_replace($TabelaPublicidadesPontos,'publicidadespontos', $retorno);

$retorno = str_replace('<br>','', $retorno);

$query = "";
$query .= " drop table ". $TabelaFotografias .";";
$query .= " drop table ". $TabelaPublicidadeImagens .";";
$query .= " drop table ". $TabelaUsuarios .";";
$query .= " drop table ". $TabelaManutencoes .";";
$query .= " drop table ". $TabelaManutencoesPontos .";";
$query .= " drop table ". $TabelaVistorias .";";
$query .= " drop table ". $TabelaVistoriasPontos .";";
$query .= " drop table ". $TabelaPublicidades .";";
$query .= " drop table ". $TabelaPublicidadesPontos .";";
$db->setQuery($query);
$db->execute();

echo $retorno;

ob_end_flush(); 
?>