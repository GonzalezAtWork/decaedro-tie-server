<?
$menu = new XMLObject();
$menu->loadXMLFromFile("config/menu.xml");

#Cria barra de menu
echo '<div id="blueline">';
echo '<ul id="menu">';

#Cria variavel para uso do login de usuario logado
$login = $_SESSION['nome_usuario'].'&nbsp;&nbsp;<small>['. $_SESSION['nome_perfil'].']</small>';

#Itera opções horizontais
for ($x=0; $x<count($menu->item); $x++) {

	#Se não tem token setado ou se token está contido na string de permissões, inclui menu
	if (!isSet($menu->item[$x]->token) || (!strpos($_SESSION['permissoes'], strtolower($menu->item[$x]->token.'.')) === false)) {
	
		#Cria o link do menu
		if (isSet($menu->item[$x]->href)) {
			$href = $menu->item[$x]->href;
		} else {
			$href = 'javascript:void();';
		}
		if($menu->item[$x]->label == "#USER#") {
			echo '<span style="float:right">';
			echo '<img src="images/pixel.png" width="1" height="28" align="left"/>';
			echo '<li><a href="'.$href.'">'.$login.'</a>';
		}else{
			echo '<span>';
			echo '<img src="images/pixel.png" width="1" height="28" align="left"/>';
			echo '<li><a href="'.$href.'">'.$menu->item[$x]->label.'</a>';
		}
		#Se tem itens verticais
		if (count($menu->item[$x]->item) > 0) {
	
			echo '<ul>';
			#Itera opções verticais
			for ($y=0; $y<count($menu->item[$x]->item); $y++) {
				
				#Se o item vertical é separador
				if (isSet($menu->item[$x]->item[$y]->splitter)) {
					#Mostra linha
					echo '<hr>';
				} else {
					#Mostra link do menu
					echo '<li><a href="'.$menu->item[$x]->item[$y]->href.'">'.$menu->item[$x]->item[$y]->label.'</a></li>';
				}
			}
			#Fecha opções verticais 
			echo '</ul>';
		}
		echo '</li>';
		if($menu->item[$x]->label == "#USER#") {
			echo '<img src="images/pixel.png" width="1" height="28" align="left"/>';
		}
		echo '</span>';
	}
}
echo '<img src="images/pixel.png" width="1" height="28" align="left"/>';

#Fecha barra de menu
echo '</ul>';
echo '</div>';		
?>