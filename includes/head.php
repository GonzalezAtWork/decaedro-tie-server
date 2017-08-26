<?php
date_default_timezone_set('America/Sao_Paulo');

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt" data-cast-api-enabled="true">
		
	<head>

		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />

		<meta http-equiv="cache-control" content="no-cache"/>
		<meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT"/>
		<meta http-equiv="pragma" content="no-cache"/>
	
		<meta name="description" content="<?php echo $app->description; ?>"/>
	
		<title><?php echo $app->description; ?></title>

		<link rel="shortcut icon" href="images/kalitera_favicon_128.png"> 
		<link rel="apple-itouch-icon" href="images/kalitera_favicon_128.png">
		<link rel="icon" type="image/png" href="images/kalitera_favicon_128.png">
		<meta itemprop="image" content="images/kalitera_favicon_128.png">

		<link rel="stylesheet" type="text/css" href="styles/jquery.modaldialog.css"/>
		<link rel="stylesheet" type="text/css" href="styles/jquery.tinybox.css"/>
		<link rel="stylesheet" type="text/css" href="styles/default.css"/>
		<link rel="stylesheet" type="text/css" href="styles/menu.css"/>
	
		<script type="text/javascript" src="javascript/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="javascript/jquery.maskedinput-1.3.min.js"></script>
		<script type="text/javascript" src="javascript/jquery.tinybox-2.0.min.js"></script>
		<script type="text/javascript" src="javascript/geral.js"></script>
	
	</head>

<body>
<?php 
#Se é index
if (strpos($_SERVER['SCRIPT_FILENAME'], 'index') > 0) {
	// destroi a sessao se forçar entrar pelo index.php
	session_destroy();
	echo '<div id="blueline"><span id="name">'.$app->description.'</span></div>';
} else {
	include('menu.php');
}
?>
<div id="contents" align="center">
