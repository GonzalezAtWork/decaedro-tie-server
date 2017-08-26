<?php
ini_set("memory_limit", "1024M");

ob_start("ob_gzhandler");

header('Access-Control-Allow-Origin: *');
header( 'Content-type: text/html; charset=UTF-8' );
header( 'Content-Encoding: gzip' );
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Description: Kalitera" );

set_time_limit(3600000);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
		
	<head>
		<title>Exec Query</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	</head>
<body>
<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	include('../classes/XMLObject.php');
	include('../classes/database.php');
	#Conexão ao banco de dados
	$db = Database::getInstance();
	//$query  = "delete from pontosTipo where (id_equipe = ".$id_equipe.") ";
	$query  = $_REQUEST["query"];
	$db->setQuery($query);
	$db->execute();
	$dados = $db->getResultSet();
	echo "<b>Query Executada:</b><br/><pre>";
	echo $query . "</pre><br/>";
	echo "<b>Resultado JSON:</b><br/><pre>";
	//echo json_encode($dados[0]);
	$bla = json_encode($dados);
	$bla = str_replace('[','[<br/>',$bla);
	$bla = str_replace('},','},<br/>',$bla);
	$bla = str_replace(']','<br/>]',$bla);
	echo $bla;
	echo "</pre><br/>";	
}
?>
<form action="exec_query.php" method="post">
	<textarea style="width:800px;height:450px;" name="query"></textarea><br/>
	<input type="submit" value="executar" style="width:800px;"/>
<form>
</body>
</html>
<?
ob_end_flush(); 
?>