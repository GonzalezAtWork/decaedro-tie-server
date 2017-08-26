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
$query = "select * from fotografias where id_foto = ". $id_foto;
$db->setQuery($query);
$db->execute();
$dados = $db->getResultSet();

$filename = "download";
$imagem = "R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";

foreach ($dados as $row) {
	$filename = $row["nome"];
	$destinationImage = $row["base64"];
}


header("Content-type: image/jpg");
header("Content-Disposition: attachment; filename=". $filename .".jpg");
header("Pragma: no-cache");
header("Expires: 0");
/*
$exif = exif_read_data('data://image/jpeg;base64,'. $imagem);
if($exif===false){
	$orientation = $exif['IFD0']['Orientation'];
	switch($orientation) {
		case 2: // horizontal flip
			$this->ImageFlip($dimg);
			break;
		case 3: // 180 rotate left
			$destinationImage = imagerotate($destinationImage, 180, -1);
			break;
		case 4: // vertical flip
			$this->ImageFlip($dimg);
			break;
		case 5: // vertical flip + 90 rotate right
			$this->ImageFlip($destinationImage);
			$destinationImage = imagerotate($destinationImage, -90, -1);
			break;
		case 6: // 90 rotate right
			$destinationImage = imagerotate($destinationImage, -90, -1);
			break;
		case 7: // horizontal flip + 90 rotate right
			$this->ImageFlip($destinationImage);
			$destinationImage = imagerotate($destinationImage, -90, -1);
			break;
		case 8: // 90 rotate left
			$destinationImage = imagerotate($destinationImage, 90, -1);
			break;
	}
}
*/
echo base64_decode($destinationImage);
?>