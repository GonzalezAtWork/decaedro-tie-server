<?php

// http://tie4.decaedro.net/autenticacao/?cpf=22243354829&senha=202cb962ac59075b964b07152d234b70&device=000.000.000.000

header("Access-Control-Allow-Origin: *");

function anti_sql_injection($str) {
	if (!is_numeric($str)) {
		$str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
		//$str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
	}
	return $str;
}

function isValidMd5($md5 ='') {
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$cpf = (isset($_REQUEST['cpf']))?$_REQUEST['cpf']:"";
$senha = (isset($_REQUEST['senha']))?$_REQUEST['senha']:"";

$senha = anti_sql_injection($senha);
$cpf = anti_sql_injection($cpf);

if( !isValidMd5($senha) ){
	$senha = md5($senha);
}

$device = (isset($_REQUEST['device']))?$_REQUEST['device']:"";
if($device == ""){
	$device = $_SERVER["REMOTE_ADDR"];
}

include('../classes/XMLObject.php');
include('../classes/database.php');

// Conexão ao banco de dados
$db = Database::getInstance();

$query  = "select s.url, u.id_usuario, u.nome, u.id_perfil, p.nome as nome_perfil ";
$query .= "from usuarios u ";
$query .= "inner join perfis p on p.id_perfil = u.id_perfil ";
$query .= "inner join servidores s on s.id_servidor = u.id_servidor ";
$query .= "where u.cpf = '". $cpf ."' and u.senha = '". $senha ."';";

$db->setQuery($query);
$db->execute();

$login = $db->getResultAsObject();

#Se não tem perfil, significa que usuário ou senha estão errados

if ($db->getRows() > 0) {

	#Buscando permissões
	$query  = "select perm.token ";
	$query .= "from permissoes as perm, perfil_permissoes as aux ";
	$query .= "where perm.id_permissao = aux.id_permissao ";
	$query .= "and aux.id_perfil = ".$login->id_perfil;

	$db->setQuery($query);
	$db->execute();

	$dados = $db->getResultSet();

	#Concatena permissões em string e pega o nome do grupo
	$permissoes = '.';
	foreach ($dados as $row) {
		$permissoes .= strtolower($row['token']).".";
	}

	$token = md5( $login->id_usuario ."_". date('U') );

	$result = array(
		"url_webservice"=>$login->url, 
		"nome_usuario"=>$login->nome, 
		"nome_perfil"=>$login->nome_perfil, 
		"username"=>($login->nome .' - '. $login->nome_perfil),
		"sessionToken"=> $token
	);

	#Gravando Log de login para auditoria
	$query  = "";
	$query .= "insert into mobile_login (";
	$query .= "		ip_address, device, id_usuario, token";
	$query .= ")values(";
	$query .= "'". $_SERVER["REMOTE_ADDR"] ."',";
	$query .= "'". $device ."',";
	$query .= "'". $login->id_usuario ."',";
	$query .= "'". $token ."'";
	$query .= ");";
	$query .= "insert into auditoria(ip_address, id_usuario, acao, obs) values('".$device."','". $login->id_usuario."','0','Logou no SisAutentica. \nToken: ". $token ."');";
	$db->setQuery($query);
	$db->execute();
} else {
	$post = "|";
	foreach ($_POST as $key => $value)
        $post .= $key.'='.$value.'&';
	$querystring = "|";
	foreach ($_REQUEST as $key => $value)
        $querystring .= $key.'='.$value.'&';
	#Erro no nome de usuário ou senha
	$result = array('erro' => 'Usuario ou senha invalida!', 'querystring' => $querystring, 'post' => $post , 'query' => $query);
}
#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecess?rio
echo json_encode($result);
?>