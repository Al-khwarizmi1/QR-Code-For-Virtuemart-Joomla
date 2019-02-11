/**
 * @name        QR code for Virtuemart
 * @version	1.1: qrcode.js
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Qrcode javascript page for validation.
 */

	//var myHeight = 200;
	var isResizable = true;
	
	function createTarget(form,total,task) 
	{
		var url = form.action+'&redirect='+task+'&val='+total;
		_target = form.target;
		_colon = _target.indexOf(":");
		if(_colon != -1) 
		{
			form.target = _target.substring(0,_colon);
			form.args = _target.substring(_colon+1);
		} 
		else if(typeof(form.args)=="undefined") 
		{
			form.args = "";
		}
		if(form.args.indexOf("{")!=-1) 
		{
			_args = form.args.split("{");
			form.args = _args[0];
			for(var i = 1; i < _args.length;i++) 
			{
				_args[i] = _args[i].split("}");
				form.args += eval(_args[i][0]) + _args[i][1];
			}
		}
		form.args = form.args.replace(/ /g,"");
		_win = window.open(url,form.target,form.args);
		if(typeof(focus)=="function")
		_win.focus();
		return true;
	}
	
	function qrcodeValidate(val)
	{
		var ErrFlage = 0;
		if(document.adminForm.boxchecked.value==0)
			{
				alert(COM_QRCODE_SELECT_CHECKBOX);
				ErrFlage = 1;
			}
	    
		if(!ErrFlage)
	    	{
	    	   document.getElementById('qrcodeAction').value = val;
	    	   document.getElementById('singleDisplay').value = val;
		       return true;
	    	}
		else
			return false;
	}
	
	function singleQrcode(val)
	{
		document.getElementById('singleDisplay').value = val;
	}
	
	function submitbutton(task)
	{
		if(task == 'cancel')
		{
			window.location.href = cancelRedirect;
		}
		if(task == 'share')
		{
			var total="";
    		for(var i=0; i < document.adminForm.elements.length; i++)
    		{
    			if(document.adminForm.elements[i].checked)
    			{
        			if(document.adminForm.elements[i].value)
        			{
    					total +=document.adminForm.elements[i].value + ","
        			}
    			}
    		}
			var formName = document.adminForm;
			createTarget(formName,total,task);
		}
		if(task == 'qrcodeGenerate')
			{
				var total="";
				for(var i=0; i < document.adminForm.elements.length; i++)
        		{
    				if(document.adminForm.elements[i].checked)
    				{
        				if(document.adminForm.elements[i].value)
        				{
    						total +=document.adminForm.elements[i].value + ","
        				}
    				}
    			}
			var selectedValue = total;
			var basePath = document.getElementById('qrcodeURL').value;
			var redirectPath = document.getElementById('currentURL').value;
			document.location.href = basePath+'&selectedValue='+selectedValue+'&redirectPath='+redirectPath;
			}
		if(task == 'print')
		{
			var total="";
    			for(var i=0; i < document.adminForm.elements.length; i++)
    			{
    				if(document.adminForm.elements[i].checked)
    				{
    					if(document.adminForm.elements[i].value)
    					{
    						total +=document.adminForm.elements[i].value + ","
    					}
    				}
    			}
			var formName = document.adminForm;
			createTarget(formName,total,task);
		}
	}
	
	var qrcode = jQuery.noConflict();
	qrcode(document).ready(function()
	    {
			qrcode("#qrcodeCategory").change(function()
					{
			        	qrcode("#listProduct").html("");
			            if(this.value != 0)
			            {
				            qrcode.post(url,{option:"com_qrcode",task:"displayCategory",category_id:this.value},function(result)
				            {
				            	qrcode("#listProduct").html(result);
				            });
			            }
			   
			        });
		
		qrcode("#sendMail").click(function(){
			qrcode(".Err").html("");
		    var ErrFlage  = 0;
		      
		       if(jQuery.trim(qrcode("#to").val()) == "")
		       {
		    	   qrcode("#emailToErr").html(toAddress);
		           ErrFlage = 1;
		       }
		       else if(!isValidEmailAddress(jQuery.trim(qrcode("#to").val())))
		       {
		    	   qrcode("#emailToErr").html(validEmail);
		           ErrFlage = 1;
		       }
		       if(!ErrFlage)
		            return true;
		       
		        else
		            return false;
		    });
	    });
	
	function isValidEmailAddress(emailAddress) 
	{
	    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	    return pattern.test(emailAddress);
	}

	function refreshandclose()
	{
		window.close();
		opener.location.reload(true);
	}
	
	function qrcheckAll(val)
	{
		
		if(val == true)
		{	
			qrcode("#ajaxQrcode [type='checkbox']").attr('checked', "checked");
			qrcode("[name='boxchecked']").val(1);
		}
		else
		{
			qrcode("#ajaxQrcode [type='checkbox']").removeAttr('checked');
			qrcode("[name='boxchecked']").val(0);	
		}
	}
	
	function getProductId(val)
	{
		qrcode('#qrcodePid').val(val);
	}
	
	function getProductList(url)
	{
		var catId = qrcode('#qrcodeCategory').val();
		var pId = qrcode('#qrcodePid').val()
		window.location.href = url+'&catId='+catId+'&pId='+pId;
	}
	
	function getProductByCategory(val,productID)
	{
		  qrcode("#listProduct").html("");
		  if(val != 0)
		  {
	            qrcode.post(url,{option:"com_qrcode",task:"displayCategory",category_id:val,product_id:productID},function(result)
	            {
	            	qrcode("#listProduct").html(result);
	            });
		  } 
	}
	
	function disableEnterKey(e)
	{
	     var key;     
	     if(window.event)
	          key = window.event.keyCode; //IE
	     else
	          key = e.which; //firefox     

	     return (key != 13);
	}
	
