<?php
header("Access-Control-Allow-Origin: *");

function anti_sql_injection($str) {
    if (!is_numeric($str)) {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        //$str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
    }
    return $str;
}

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$id_usuario = $_REQUEST['id_usuario'];

include('../classes/XMLObject.php');
include('../classes/database.php');

#Conexão ao banco de dados
$db = Database::getInstance();


$query  = " select oss.*, eq.equipe, rt.roteiro, vis.qtd_pontos from oss ";

$query .= " left join ( ";
$query .= "		select id_os, string_agg(nome,', ') as equipe from ( ";
$query .= "			select id_os, id_equipe from ocorrencias where id_os is not null group by id_equipe,id_os ";
$query .= "		) as ocor  ";
$query .= "		inner join usuarios on usuarios.id_usuario = ocor.id_equipe  ";
$query .= "		group by id_os  ";
$query .= " ) as eq on oss.id_os = eq.id_os";

$query .= " left join ( ";
$query .= "		select id_os, string_agg(nome,', ') as roteiro from (";
$query .= "			select id_os, id_roteiro from ocorrencias ";
$query .= "			inner join pontos on pontos.id_ponto = ocorrencias.id_ponto";
$query .= "			where id_os is not null group by id_roteiro ,id_os";
$query .= "		) as rot ";
$query .= "		inner join roteiros on roteiros.id_roteiro = rot.id_roteiro ";
$query .= "		group by id_os ";
$query .= " ) as rt on oss.id_os = rt.id_os";

$query .= " left join ( ";
$query .= "		select id_os, sum(1) as qtd_pontos from ocorrencias group by id_os ";
$query .= " ) as vis on oss.id_os = vis.id_os";
$query .= " where oss.ativo = true";
$query .= " order by oss.data desc, oss.id_os desc ";

//$query .= "where u.id_usuario = '".anti_sql_injection($id_usuario)."';";

$db->setQuery($query);
$db->execute();

$dados = $db->getResultSet();

#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);