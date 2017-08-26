<?
class Dropdown {
	
############################################################################	

	public function getHTMLFromQuery($query = NULL, $selected = NULL, $firstBlank = TRUE, $name = NULL, $functions = NULL) {

		#Se não tem query, retorna nulo
		if (is_null($query)) {
			return '';
		}
		
		if (is_null($name)) {
			$name = substr($query, strpos($query, "from") + 5);
			$name = substr($name, 0, (strpos($name, " ")==0 ? strlen($name) : strpos($name, " ")));
		}

		$name = ' name="'.$name.'" id="'.$name.'"';
		
		$functions = (is_null($functions) ? '' : ' '.$functions);
		
		#Conecta na base de dados
		$db = Database::getInstance();
		$db->setQuery($query);
		$db->execute();
		
		$result = $db->getResultSet();
		
		#Return string
		$html  = '<select'.$name.$functions.'>';
		
		if ($firstBlank === TRUE) {
			$html .= '<option value="">&nbsp;</option>';
		}

		foreach ($result as $row) {
			$html .= '<option value="'.$row['code'].'"'.((trim(strtolower($selected)) == trim(strtolower($row['code']))) ? ' selected' : '').'>'.$row['label'].'</option>';
		}

		$html .= '</select>';
      return $html;
      
    }
    
############################################################################

    public function getHTMLFromInterval($name = NULL, $start = NULL, $finish = NULL, $selected = NULL, $functions = NULL) {

		if (is_null($start) || is_null($finish)) {
			return '';
		}
		$name = (is_null($name) ? '' : ' name="'.$name.'" id="'.$name.'"');
		$functions = (is_null($functions) ? '' : ' '.$functions);
				
		$pass = (($start < $finish) ? 1 : -1);
		//Return string
		$html  = '<select'.$name.$functions.'>';
		$html .= '<option value="">&nbsp;</option>';
		for ($iterator = $start; (($start < $finish) ? ($iterator <= $finish) : ($iterator >= $finish)); $pass) {
			$html .= '<option value="'.$iterator.'"'.(($selected == $iterator) ? ' selected' : '').'>'.$iterator.'</option>';
			$iterator = $iterator + $pass; 
		}
		$html .= '</select>';
      return $html;
    }
    
############################################################################

	public function getHTMLFromArray($array = NULL, $selected = NULL, $name = NULL, $showFirstElement = TRUE, $functions = NULL) {

		if (is_null($array)) {
			
			return '';

		} else {
		
			$name = ((is_null($name)) ? '' : ' name="'.$name.'" id="'.$name.'"');
			
			$functions = ((is_null($functions)) ? '' : ' '.$functions);
			
			#Return string
			$html  = '<select'.$name.$functions.'>';
	
			if ($showFirstElement === TRUE) {
				$html .= '<option value="">&nbsp;</option>';
	    	}
	
			$counter = 0;
			foreach ($array as $element) {
				$counter++;
				$html .= '<option value="'.$counter.'"'.(($selected == $counter) ? ' selected' : '').'>'.$element.'</option>';
			}
			$html .= '</select>';
	      return $html;
		}
    }
    
############################################################################
   
}
?>