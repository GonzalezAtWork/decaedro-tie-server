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

$id_foto = (isset($_REQUEST['id_foto']))?$_REQUEST['id_foto']:"0";
$id_foto = anti_sql_injection( $id_foto );

include('classes/XMLObject.php');
include('classes/database.php');
$db = Database::getInstance();

$query  = "";
$query .= " select ";
$query .= "		to_char(ocorrencias.dt_lastupdate,'dd/MM/yyyy hh:ss') as data_formatada,  ";
$query .= "		fotografias.nome , ";
$query .= "		fotografias.base64 , ";
$query .= "		pontos.codigo_abrigo , ";
$query .= "		pontos.codigo_novo , ";
$query .= "		pontos.endereco , ";
$query .= "		vistoriasitens.nome as nome_item , ";
$query .= "		fotografias.id_item , ";
$query .= "		ocorrencias.semanapublicidade, ";
$query .= "		ocorrencias.nomeimagenspublicidade ";
$query .= "	from fotografias ";
$query .= " inner join ocorrencias on fotografias.id_ocorrencia = ocorrencias.id_ocorrencia ";
$query .= " inner join pontos on ocorrencias.id_ponto = pontos.id_ponto ";
$query .= " inner join vistoriasitens on fotografias.id_item = vistoriasitens.id_item ";
$query .= " where id_foto = ". $id_foto;

$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

$filename = "download";
$imagem = "R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
$variacao = 0;
foreach ($dados as $row) {
	$filename = $row["nome"];
	$destinationImage = $row["base64"];
	$data = $row["data_formatada"];
	$simak = $row["codigo_abrigo"];
	$otima = $row["codigo_novo"];
	$semana = $row["semanapublicidade"];
	$endereco = $row["endereco"];
	$tipo = $row["nome_item"];
	$id_item = $row["id_item"];
	$motivo = $row["nomeimagenspublicidade"];

	if($id_item == "51" || $id_item == "52"){
		$variacao = 320;
	}
}

$filename = $semana . "_" . $otima . "_" . str_replace("CAIXA ","",str_replace(" FACE ","_",str_replace("NTERNA","",str_replace("XTERNA","",$row["nome_item"]))));

$motivo = explode("|".$id_item.",", "VAZIO|".$motivo);
$motivo = explode(";", $motivo[1]);
$motivo = explode("|", $motivo[1]);
$motivo = $motivo[0];



$im = imagecreatefromstring( base64_decode($destinationImage) );

header("Content-type: image/jpg");
header("Content-Disposition: attachment; filename=". $filename .".jpg");
header("Pragma: no-cache");
header("Expires: 0");
/*
$exif = exif_read_data( $im );
if(!empty($exif['Orientation'])) {
    switch($exif['Orientation']) {
        case 8:
            $im = imagerotate($im,90,0);
            break;
        case 3:
            $im = imagerotate($im,180,0);
            break;
        case 6:
            $im = imagerotate($im,-90,0);
            break;
    }
}
*/
$thumb = imagecreatetruecolor(667,500);
ImageCopyResampled($thumb,$im,0,0,0,0,667,500,ImageSX($im),ImageSY($im));

imagedestroy($im);
// Create some colors
$black = imagecolorallocate($thumb, 0, 0, 0);
$grey = imagecolorallocate($thumb, 128, 128, 128);
$yellow = imagecolorallocate($thumb, 255, 255, 0);
$white = imagecolorallocate($thumb, 255, 255, 255);

$text = '';
$text .= 'DATA:'. chr(13).chr(10);
$text .= 'SIMAK:'. chr(13).chr(10);
$text .= 'OTIMA:'. chr(13).chr(10);
$text .= 'ENDEREÇO:'. chr(13).chr(10);
$text .= 'TIPO:'. chr(13).chr(10);
$text .= 'MOTIVO:';
$font = 'verdanab.ttf';
imagettftext($thumb, 10, 0, $variacao + 21, 391, $black, $font, $text);  // SOMBRA
imagettftext($thumb, 10, 0, $variacao + 20, 390, $yellow, $font, $text); // TEXTO

$text = '';
$text .= $data . chr(13).chr(10);
$text .= $simak . chr(13).chr(10);
$text .= $otima . chr(13).chr(10);
$text .= $endereco . chr(13).chr(10);
$text .= $tipo . chr(13).chr(10);
$text .= $motivo;
$font = 'verdana.ttf';
imagettftext($thumb, 10, 0, $variacao + 121, 391, $black, $font, $text);  // SOMBRA
imagettftext($thumb, 10, 0, $variacao + 120, 390, $yellow, $font, $text); // TEXTO

imagejpeg($thumb);
imagedestroy($thumb);
?>