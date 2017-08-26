<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

function anti_sql_injection($str) {
    if (!is_numeric($str)) {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        //$str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
    }
    return $str;
}

$nome = (isset($_REQUEST['nome']))?$_REQUEST['nome']:"";
$nome = anti_sql_injection( $nome );
$nome = base64_decode ( $nome );

include('classes/XMLObject.php');
include('classes/database.php');
$db = Database::getInstance();
$query = "select * from publicidadeimagens where nome = '". $nome ."'";
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

$filename = "download";
$imagem = "R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";

foreach ($dados as $row) {
	$filename = $row["nome"];
	$filename = urlencode($filename);
	$destinationImage = $row["imagem"];
}


header("Content-type: image/jpg");
header("Content-Disposition: attachment; filename=". $filename .".jpg");
header("Pragma: no-cache");
header("Expires: 0");

echo base64_decode($destinationImage);

?>