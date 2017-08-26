<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	</head>
<body>
<?php

include('classes/database.php');

include('classes/XMLObject.php');

$app = new XMLObject();
$app->loadXMLFromFile("config/application.xml");

$db = Database::getInstance();

//$endereco = 'http://www.buscacep.correios.com.br/servicos/dnec/consultaEnderecoAction.do';
//$postvars = 'TipoCep=ALL&semelhante=N&cfm=1&Metodo=listaLogradouro&TipoConsulta=relaxation&StartRow=1&EndRow=10&relaxation=';

$endereco = 'http://www.buscacep.correios.com.br/servicos/dnec/consultaLogradouroAction.do';
$postvars = 'UF=SP&cfm=1&Metodo=listaLogradouro&TipoConsulta=logradouro&StartRow=1&EndRow=10&Localidade=Sao%20Paulo&Tipo=&Numero=&Logradouro=';

$query  = " select a.*, b.nome as padrao_nome, c.nome as tipo_nome, c.totem as tipo_totem, s.status_nome from pontos a ";
$query .= " left join pontosPadrao b on a.id_padrao = b.id_padrao ";
$query .= " left join pontosTipo c on b.id_tipo = c.id_tipo ";
$query .= " inner join ( ";
$query .= " 	select A.id_ponto, C.nome as status_nome from pontosStatusHistorico A ";
$query .= " 	inner join ( ";
$query .= " 		SELECT 	id_ponto , max(data) as data ";
$query .= " 		FROM pontosStatusHistorico ";
$query .= " 		GROUP BY id_ponto ";
$query .= " 	) B ON B.id_ponto = a.id_ponto and b.data = a.data ";
$query .= " 	inner join pontosStatus C on A.id_status = C.id_status ";
$query .= " ) s on a.id_ponto = s.id_ponto  ";
$query .= " where a.ativo = TRUE and a.cep = '' ";
$query .= "  order by a.endereco ";
$query .= "  LIMIT 1 ";

$db->setQuery($query);
$db->execute();

$result = $db->getResultSet();

foreach ($result as $row) {
	$buscar = $row["endereco"];

	$buscar = str_replace(strstr($buscar,'X'),'',$buscar);
	$buscar = str_replace(strstr($buscar,','),'',$buscar);

	echo "<b>Endereço:</b> " . $buscar;
	define ('HOSTNAME', $endereco);

	//$url = HOSTNAME.$path;
	$url = $endereco;

	$session = curl_init($url);
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $postvars. $buscar);
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	$xml = curl_exec($session);

	curl_close($session);

	echo '<br><textarea rows=30 cols=100>'. $xml .'</textarea><br>';

	if(strrpos($xml,'o foi encontrado') > 0){
		$xml = 'NOT FOUND';
	}elseif(strrpos($xml,'LOGRADOURO NAO ENCONTRADO') > 0){
		$xml = 'NOT FOUND';
	}elseif(strrpos($xml,'500 Internal Server Error') > 0){
		$xml = 'WEB ERROR';
	}elseif(strrpos($xml,'SQLException') > 0){
		$xml = 'SQL ERROR';
	}else{
		$xml = strstr($xml,'<td width="65" style="padding: 2px">');
		$xml = str_replace(strstr($xml,'</td>'),'',$xml);
		$xml = str_replace('<td width="65" style="padding: 2px">','',$xml);
	}
	echo '<br/><b>CEP:</b> '. $xml;
	$new_query = "update pontos set CEP = '". $xml ."' where id_ponto = " . $row["id_ponto"];
	$db->setQuery($new_query);
	$db->execute();
	//echo $new_query;
}
?>
<script language="javascript">location.href="CEP_para_pontos.php"</script>
</body>
</html>