<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

#Definição de constantes
define('SIMAK', 0);
define('FACE', 1);
define('IMAGEM', 2);

$csv = $_REQUEST['csv'];
$delimiterIndex = $_REQUEST['delimitador'];
$semana =  $_REQUEST['semana'];
$properDelimiter = array(',', ';', ':', chr(8), chr(32));
$newInserts = "";

include('../classes/XMLObject.php');
include('../classes/database.php');

#Definição da query
$query  = "insert into publicidadeVeiculacao (simak, caixa, face, semana, nome_imagem) values ";

#Quebrando linhas
$aLines = explode(chr(10), $csv);

foreach ($aLines as $line) {

	#Quebrando a linha em campos
	$aValues = explode($properDelimiter[$delimiterIndex], $line);

	#Se tem um número de campos menor do que o esperado
	if ($semana == 0 && count($aValues) != 9) {

		#Retorna erro
		die('{"processado":"false", "mensagem":"Número incorreto de campos a importar."}');

	} else {

		#Se o campo SIMAK não é numérico, então é a primeira linha do CSV que contém o nome dos campos e deve ser ignorada
		if (is_numeric($aValues[SIMAK])) {
			$query .= "(" . $aValues[SIMAK] . ",'A','" . $aValues[FACE] . "'," . $semana . ",'" . $aValues[IMAGEM] . "'),";
		}

	}

}

#Finaliza a concatenação da query tira a vírgula do final
$query = substr($query, 0, -1).";";

#Conexão ao banco de dados
$db = Database::getInstance();

$db->setQuery($query);

try {

	$db->execute();
	$result = $db->getResultSet();
/*
	try {

		#Acertando os SIMAKs que não foram enviados duas faces
		$query  = "select simak, face from publicidadeVeiculacao where semana=".$semana." and ano=2013 order by simak asc, face desc";
		$db->setQuery($query);
		$db->execute();
		$result = $db->getResultSet();

		for ($i = 0; $i <= strlen($result); $i++) {

			#Carrega primeira linha
			$firstSimak = $result[$i]['simak']);
			$firstFace = $result[$i]['face']);

			#Avança uma linha
			$i++;

			#Carrega primeira linha
			$secondSimak = $result[$i]['simak']);
			$secondFace = $result[$i]['face']);

			#Verifica diferença de simaks entre linhas
			if ($firstSimak != $secondSimak) {
				$newQuery = "select simak, face, nome_imagem from publicidadeVeiculacao where semana=".$semana-1." and ano=2013 and simak='".$firstSimak."' and face!='".$firstFace."'";

				try {

					$db->setQuery($newQuery);
					$db->execute();
					$simaksPermanencia = $db->getResultSet();

					$newInserts .= "(" . $simaksPermanencia["simak"] . ",'A','" . $simaksPermanencia["face"] . "'," . $semana . ",'" . $simaksPermanencia["nome_imagem"] . "', 1),";

				} catch (Exception $e) {

					echo $e->getMessage();

				}

				#Volta linha para não perder o simak
				$i--;

			}

		}

	} catch (Exception $e) {

		echo $e->getMessage();

	}

*/
	#Retornando apenas o primeiro elemento do array para evitar array bidimensional denecessário
	die('{"processado":"true"}');

} catch(Exception $e) {

	echo $e->getMessage();

}


?>