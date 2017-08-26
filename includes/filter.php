<?php 
$drop = new Dropdown();
$select = $drop->getHTMLFromArray($aFilterLabels, $filterField, 'filterfield', FALSE);
?>
<div id="filtercontainer">
   <span id="filterlabel"><?php echo $select;?></span>
   <span><input type="text" name="filtertext" id="filtertext" class="medium_field" value="<?php echo $filterText;?>"></span>
   <span><input type="button" name="filterbutton" id="filterbutton" value="Filtrar"></span>
</div>