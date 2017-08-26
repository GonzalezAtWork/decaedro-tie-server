<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

#Inicia sessão
session_start();

#Se não tem sessão nem veio do login
if (!(isset($_SESSION['id_usuario']) || isset($_REQUEST['permissoes']))) {

	#Volta para login
	header('location:index.php');

}

#Se tem o campo hidden de permissões, é porque veio do login
if (isset($_REQUEST['permissoes'])) {
	$_SESSION['id_usuario'] = $_REQUEST['id_usuario'];
	$_SESSION['nome_usuario'] = $_REQUEST['nome_usuario'];
	$_SESSION['id_perfil'] = $_REQUEST['id_perfil'];
	$_SESSION['nome_perfil'] = $_REQUEST['nome_perfil'];
	$_SESSION['permissoes'] = $_REQUEST['permissoes'];
}

#Inclusão de classes
include('classes/database.php');
include('classes/dropdown.php');
include('classes/XMLObject.php');

$app = new XMLObject();
$app->loadXMLFromFile("config/application.xml");

#Pega a página atual
$currentPage = (isset($_REQUEST['page']))?$_REQUEST['page']:1;

#Quantidade de registros mostrados por página
$qtd_por_pagina = (isset($_REQUEST['qtd_por_pagina']))?$_REQUEST['qtd_por_pagina']: $app->recordsToShow ;

#Primeiro registro da paginação
$offset = (($currentPage * $qtd_por_pagina) - $qtd_por_pagina);

#Se não tem campo de filtro, passa nome como default
$filterField = (isset($_REQUEST['filterfield']))?$_REQUEST['filterfield']:1;

#Se não tem texto de filtro, passa string em branco
$filterText = (isset($_REQUEST['filtertext']))?$_REQUEST['filtertext']:"";

#Se não tem campo de ordenacao, passa nome como default
$orderField = (isset($_REQUEST['orderfield']))?$_REQUEST['orderfield']:1;

#Se não tem ordem de registros, passa ordem crescente (decrescente falso)
$desc = (isset($_REQUEST['desc']))?$_REQUEST['desc']:"false";

include("includes/general.php");
include("includes/head.php");

#Seleciona a ação correta
if (isset($_GET['action'])) {
	
	$action = $_GET['action'];

	#Ações válidas - Na próxima versão, carregar do banco de dados, junto com xml de menus
	
	$legalActions = ".";
	$legalActions .= "inbox.outbox.";
	$legalActions .= "mydata.changepass.devicesMapa.";
	$legalActions .= "groups.permissions.users.users_edit.";
	$legalActions .= "perfis.perfis_edit.perfisPermissoes_edit.";
	$legalActions .= "pontos.pontos_edit.pontos_fotos.pontos_publicidade.pontos_import.";

	$legalActions .= "pontosStatus.pontosStatus_edit.";
	$legalActions .= "pontosTipo.pontosTipo_edit.";
	$legalActions .= "pontosPadrao.pontosPadrao_edit.";
	$legalActions .= "pontosMapa.pontosStatusHistorico.";

	$legalActions .= "relatorio_geral.";

	$legalActions .= "make_backup.insere_foto.";
	$legalActions .= "publicidade_import.";

	$legalActions .= "auditoria.";
	$legalActions .= "zonas.zonas_edit.";
	$legalActions .= "limiteTerreno.limiteTerreno_edit.";
	$legalActions .= "pisoCalcada.pisoCalcada_edit.";
	$legalActions .= "interferencias.interferencias_edit.";
	$legalActions .= "equipes.equipes_edit.equipes_print.";
	$legalActions .= "regionais.regionais_edit.";
	$legalActions .= "inclinacoes.inclinacoes_edit.";
	$legalActions .= "bairros.bairros_edit.";
	$legalActions .= "roteiros.roteiros_edit.roteirosMapa.roteirosMapaCadastra.";
	$legalActions .= "vistorias.vistorias_andamento.vistorias_edit.vistoriasItens.vistoriasItens_edit.vistoriasGuiaPreenche.vistoriasPontoPreenche.";
	$legalActions .= "oss.oss_new.oss_edit.";
	$legalActions .= "ocorrencias.ocorrencias_aberto.ocorrencias_edit.";
	$legalActions .= "publicidade_checkin.publicidade_andamento.publicidadeVeiculacao.publicidadeVeiculacao_edit.publicidadeVeiculacao_import.publicidadeImagens.publicidadeImagens_edit.";
	$legalActions .= "exec_query.";

	if (strpos($legalActions, $action.'.') === false) {
	    $action = "400";
	} else {
		$_SESSION['action'] = $action;
	}

} else {

	#Se action não veio por GET, pode estar na sessão
	if (isset($_SESSION['action'])) {
		
		#É preciso verificar se veio da alteração de senha
		#Se veio, deve ir para myData
		$action = $_SESSION['action'];

	} else {

		#Se não tem action em lugar nenhum, então é mydata
		$action = "mydata";
		$_SESSION['action'] = "mydata";

	}

}

$_SESSION["action"] = $action;

include("actions/".$action.".php");

include("includes/tail.php");
?> 