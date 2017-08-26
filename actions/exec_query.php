<style>
	html {
		overflow-y: scroll;
	}
	th {
		font-family:Verdana, Helvetica, sans-serif;
		font-size:9pt;
		background-color:#005C95;
		color:#FFFFFF;
		text-align:left;
	}
	td {
		font-family:Verdana, Helvetica, sans-serif;
		font-size:9pt;
	}
	pre {
		font-family:"Courier New", Courier, monospace;
		font-size:9pt;
	}
	#queryWrapper {
		display:inline-block;
		position:relative;
		margin:0px;
		margin-top:12px;
		padding:0px;
		width:100%;
		height:150px;
	}
	#queryContainer {
		display:inline-block;
		position:relative;
		float:left;
		width:650px;
		height:150px;
		margin-left:162px;
	}
	#queryButton {
		display:inline-block;
		position:relative;
		float:left;
		width:134px;
		text-align:center;
	}
	#queryMessage {
		display:inline-block;
		position:relative;
		float:left;
		overflow:scroll;
		font-family:Verdana, Helvetica, sans-serif;
		font-size:9pt;
		width:389px;
		font-size:10pt;
		border: 1px solid #ABADB3;
		height:139px;
		text-align:left;
		padding:4px;
	}
	#query {
		width:98%;
		height:98%;
		margin:0px;
		padding:0px;
	}
	#submit {
		margin-top:60px;
	}
	#tabContainer {
		display:inline-block;
		position:relative;
		width:100%;
		height:32px;
		margin-top:12px;
	}
	#resultContainer {
		display:inline-block;
		position:relative;
		clear:both;
	}
	#divJSON {
		border:2px solid #005C95;
	}
	.tab {
		display:inline-block;
		position:relative;
		float:left;
		width:100px;
		padding:6px;
		border:1px solid #005C95;
		font-family:Verdana, Helvetica, sans-serif;
		font-size:12pt;
		font-weight:bold;
		cursor:pointer;
		background-color:#7FADC9;
		color:#FFFFFF;
	}
	.invisible {
		display:none;
	}
	.active {
		background-color:#005C95;
		color:#FFFFFF;
	}
	.highlight {
		background-color:#77CC00;
		color:#003300;
	}
	.even {
		background-color:#FFFF99;
	}
	.odd {
		background-color:#FFFFFF;
	}
	.lineNumber {
		font-family:Verdana, Helvetica, sans-serif;
		font-size:9pt;
		background-color:#005C95;
		color:#FFFFFF;
		text-align:center;
	}
</style>

<script language="javascript">

$(document).ready(function() {

	$( ".tab" )
		.mouseover(function() {
			$(this).addClass('highlight');
		})
		.mouseout(function() {
			$(this).removeClass('highlight');
		});

	$("#showTable")
		.click(function() {
			$(this).addClass('active');
			$("#showJSON").removeClass('active');
			$("#divJSON").addClass('invisible');
			$("#divTable").removeClass('invisible');
		})
		.addClass('active');

	$("#showJSON")
		.click(function() {
			$(this).addClass('active');
			$("#showTable").removeClass('active');
			$("#divTable").addClass('invisible');
			$("#divJSON").removeClass('invisible');
		})

	$("#divJSON").addClass('invisible');
	$("#query").focus();

})
</script>
<?php

error_reporting(0);
date_default_timezone_set('America/Sao_Paulo');

$message = "";
$query = "";
$resultJSON = "";
$resultTable = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

	//include('../classes/XMLObject.php');
	//include('../classes/database.php');

	#Conexão ao banco de dados
	$db = Database::getInstance();

	$query = $_REQUEST["query"];

	if (empty($query)) {

		$message = "Query nula.";

	} else {

		try {

			$db->setQuery($query);
			$db->execute();

			$dados = $db->getResultSet();

			if (empty($dados)) {

				$message = "Nenhuma linha foi retornada.";

			} else {

				$message = "Query executada com sucesso às ".date("H:i\h").".";
				$linhas = $db->getRows();
				if ($linhas > 0) {
					if ($linhas == 1) {
						$message .= "<br>Foi retornada uma linha.";
					} else {
						$message .= "<br>Foram retornadas ".$linhas." linhas.";
					}
				} else {
					$message .= "Nenhuma linha foi retornada.";
				}

				$temp = json_encode($dados);
				$temp = str_replace('[','[<br/>',$temp);
				$temp = str_replace('},','},<br/>',$temp);
				$temp = str_replace(']','<br/>]',$temp);
				$resultJSON = $temp;


				$resultTable = '<table cellpadding="4" cellspacing="1" border="0">';

				$resultTable .= '<thead>';
				$resultTable .= '<tr>';
				$resultTable .= '<th>&nbsp;</th>';

				$campos = $db->getFieldNames();
				for ($f=1; $f <= count($campos); $f++) {
					$resultTable .= "<th>(".$f.") ".$campos[$f-1]."</th>";
				}

				$resultTable .= '</tr>';
				$resultTable .= '</thead>';

				$resultTable .= '<tbody>';

				for ($f=0; $f < count($dados); $f++) {
					$resultTable .= '<tr class="'.(($f % 2) == 0 ? "even" : "odd").'">';
					$resultTable .= '<td class="lineNumber">'.($f+1).'</td>';
					foreach ($dados[$f] as $coluna) {
						$resultTable .= "<td>".$coluna."</td>";
					}
					$resultTable .= "</tr>";
				}

				$resultTable .= '</tbody>';
				$resultTable .= "</table>";
				$resultTable .= "<br/>";

			}

		} catch (Exception $e) {

			$message = $e->getMessage();
		}

	}

}
?>

<body>

	<div align="center">
		<div id="queryWrapper">
			<form method="post">
				<div id="queryContainer">
					<textarea name="query" id="query"><?php echo $query;?></textarea>
				</div>
				<div id="queryButton">
					<div><input type="submit" id="submit" value="Executar"/></div>
				</div>
				<div id="queryMessage">
					<?php echo $message;?>
				</div>
			<form>
		</div>
	</div>

	<?
	if (!empty($resultTable)) {
		?>
		<div align="left">
			<div id="tabContainer">
				<div id="showTable" class="tab">Tabela</div>
				<div id="showJSON" class="tab">JSON</div>
			</div>

			<div id="resultContainer">
				<div id="divTable"><pre><? echo $resultTable;?></pre></div>
				<div id="divJSON"><pre><? echo $resultJSON;?></pre></div>
			</div>
		</div>
		<?
	}
	?>

</body>

</html>