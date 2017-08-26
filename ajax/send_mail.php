<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$to = $_REQUEST['to'];
	$subject = $_REQUEST['subject'];
	$body  = $_REQUEST['body'];
	$headers = "From: Kalitera <contato@kalitera.com.br>"."\r\n"."X-Mailer: php";

	if (mail($to, $subject, $body, $headers)) {
		echo "<B>ENVIADO:</B> TRUE <br/>";	
	} else {
		echo "<B>ENVIADO:</B> FALSE <br/>";
	}
}
?>
<form action="send_mail.php" method="post">
	<input style="width:500px;" type="text" name="to" value="to"/><br/>
	<input style="width:500px;" type="text" name="subject" value="subject"/><br/>
	<textarea style="width:500px;height:350px;" name="body"></textarea><br/>
	<input type="submit" value="executar" style="width:500px;"/>
<form>