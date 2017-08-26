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

$query = "";
$query .= " select pontos.*, ocorrencias.*, roteiros.nome as roteiro, usuarios.nome as equipe from ocorrencias ";
$query .= " left join pontos on pontos.id_ponto = ocorrencias.id_ponto ";
$query .= " left join roteiros on pontos.id_roteiro = roteiros.id_roteiro ";
$query .= " left join vistorias on vistorias.id_vistoria = ocorrencias.id_vistoria ";
$query .= " left join usuarios on ocorrencias.id_equipe = usuarios.id_usuario ";
$query .= " where ";
$query .= " id_os is null and ";	
$query .= " ocorrencias.executada = false and gerar_os = true and ( ( ocorrencias.id_vistoria is not null and vistorias.executada = true ) or ( ocorrencias.id_vistoria is null ) ) ";	
$query .= " order by ocorrencias.data ";

//$query .= "where u.id_usuario = '".anti_sql_injection($id_usuario)."';";

$db->setQuery($query);
$db->execute();

$dados = $db->getResultSet();

#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($dados[0]);