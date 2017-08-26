 var _qtdSel = 2;
 var _qtdGeral = 0;
 
 function _limpaComboBox(ComboBox)
 {
	var select = ComboBox;
	var options = select.attr('options');
	
	$('option', select).remove();
	$('optgroup', select).remove();
 }
 
 function _ativaObjetos(CampoAtivo)
 {
	 document.getElementById(CampoAtivo).disabled = false
 }

 function _ajaxCarregaComboBox(UrlPage, ComboBox, stCampoId, stCampoDescricao)
 {
	 $('body').css('cursor', 'wait');
	 
	 _limpaComboBox(ComboBox);
	 ComboBox.attr("disabled", true);
	 
	 $.ajax
	 ({
		 url: UrlPage,
		 success: function(Result)
		 {					 
		 	var _ResultAjax = $.evalJSON(Result);
		 	
		 	if( _ResultAjax != false ) 
		 	{
		 		ComboBox.append("<option value=''> -- Selecione -- </option>");
		 		for(i=0; i<_ResultAjax.length; i++) 
		 		{
		 			ComboBox.append("<option value='" + _ResultAjax[i][stCampoId] + "'>" + _ResultAjax[i][stCampoDescricao] + "</option>");
		 		}
		 		
		 		ComboBox.attr("disabled", false);
		 	} 
		 	else 
		 	{
		 		ComboBox.append("<option value=''></option>");
		 	}
		 	
		 	$('body').css('cursor', 'default');
		 }
	 });
 }
 
 function _ajaxCarregaCheckBox(UrlPage, LocalName, stCampoId, stCampoDescricao, stGroup)
 {
	 $.ajax
	 ({
		 url: UrlPage,
		 success: function(Result)
		 {
		 	var _ResultAjax = $.evalJSON(Result);					 	
		 	
		 	if( _ResultAjax != false )
		 	{
		 		LocalName.html('');
		 		for(i=0; i<_ResultAjax.length; i++)	
		 		{
		 			if( _ResultAjax[i]['checked'] == 1 )
		 				LocalName.append("<input type='checkbox' onclick=\"javacript:_ativaObjetos('salvar');\" name='getCheckRedeProximidade[]' id='getCheckRedeProximidade[]' value='"+_ResultAjax[i][stCampoId]+"' checked />"+ _ResultAjax[i][stCampoDescricao] +"<br />");
		 			else
		 				LocalName.append("<input type='checkbox' onclick=\"javacript:_ativaObjetos('salvar');\" name='getCheckRedeProximidade[]' id='getCheckRedeProximidade[]' value='"+_ResultAjax[i][stCampoId]+"' />"+ _ResultAjax[i][stCampoDescricao] +"<br />");
		 		}
		 	}
		 	$('body').css('cursor', 'default');
		 }
	 });
	 
 }
 
 function _ajaxCarregaComboBoxGroup(UrlPage, ComboBox, stCampoId, stCampoDescricao, stGroup)
 {
	 $.ajax
	 ({
		 url: UrlPage,
		 success: function(Result)
		 {					 
		 	_limpaComboBox(ComboBox);
		 	var _OptGroup = "";
		 	var _ResultAjax = $.evalJSON(Result);

		 	if( _ResultAjax != false )
		 	{
		 		for(i=0; i<=_ResultAjax.length; i++)
		 		{
		 			if( _ResultAjax[i][stCampoDescricao] != '' )
		 			{
		 				if(_OptGroup == "" || _OptGroup != _ResultAjax[i][stGroup])
		 				{
		 					_OptGroup = _ResultAjax[i][stGroup];
		 					ComboBox.append("<optgroup label='"+ _ResultAjax[i][stGroup] +"'>");
		 				}	
		 				ComboBox.append("<option value='"+ _ResultAjax[i][stCampoId] +"'>&nbsp;&nbsp;&nbsp;"+ _ResultAjax[i][stCampoDescricao] +"</option>");
		 			}
		 			else
		 				ComboBox.append("<option value='"+ _ResultAjax[i][stCampoId] +"'>&nbsp;&nbsp;&nbsp;"+ _ResultAjax[i][stCampoDescricao] +"</option>");
		 				
		 		}
		 	}
		 }
	 });
 }
