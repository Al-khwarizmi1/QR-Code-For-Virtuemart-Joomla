<?php 
/**
 * @name        QR code for Virtuemart
 * @version	1.1: style1.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Style page for print view.
 */

require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'printSettings.php';

	$printDetails = JRequest::getVar('val');
	$productId = explode(',', $printDetails);
	array_pop($productId);
	$model = $this->getModel('qrcode');
	$productDetailsById = $model->printQrcodeDetails($productId);

?>

<style type="text/css" media="all">
html,body {
	background-color:#bababa;
	height: <?php echo (count($productDetailsById) ==1)?'500px':'inherit';?>
	}
@media all{
@page {  size:<?php echo conf__QRCODE_PRINT_SIZE.' '.conf__QRCODE_PRINT_ORIENTATION?>;}

}
@media print{
#printButton{display:none;}
body{margin:0px auto;padding: 0px !important;}
<?php if(conf__QRCODE_PRINT_SIZE == "A4"){?>
#productImage img{width:33mm !important;}
#qrcodeImage img{width:30mm !important;height: 30mm !important;}
#productDetails{width:110mm !important}
<?php } else if(conf__QRCODE_PRINT_SIZE == "A3"){?>
#productImage img{width:50mm !important;}
#qrcodeImage img{width:50mm !important;height: 50mm !important;}
#productDetails{margin-left: 5px;}
#productDetails{width:150mm !important}
<?php } else if(conf__QRCODE_PRINT_SIZE == "A5"){?>
#productImage img{width:25mm !important;}
#qrcodeImage img{width:25mm !important;height: 25mm !important;}
#productDetails{width:60mm !important;margin-left: 5px;margin-right: 5px;}
<?php }?>
}

html,body{
-webkit-border-image:url(<?php echo JURI::base().'components/com_qrcode/icons/gray.jpg'?>) 100 100 20 20 !important;
        -moz-border-image:url(<?php echo JURI::base().'components/com_qrcode/icons/gray.jpg'?>) 100 100 20 20 !important;
        border-image:url(<?php echo JURI::base().'components/com_qrcode/icons/gray.jpg'?>) 100 100 20 20 !important;
      
}
</style>	
		    	<a id="printButton" rel="nofollow" onclick="window.print();" title="<?php echo JText::_('COM_QRCODE_PRINT') ?>" href="javascript:void(0);" ><img width="30px" alt="<?php echo JText::_('COM_QRCODE_PRINT') ?>" src="<?php echo JURI::root() . "images/M_images/printButton.png"; ?>"></a>
		    	<br><br>
		    	
		    	<?php
		    	
				    		
		    	for($i = 0;$i < count($productDetailsById);$i++)
				{
				    	//echo "<div>";
				    	echo "<div id='productImage' style='float:left;margin-right:0px;margin-left:0px;'>";
				    	$img = $productDetailsById[$i]['product_full_image'];
				    	if(!empty($img))
				    	{
							echo "<img width='100px' style='border: 2px solid;border-color:silver' alt='No image' src=\"".JURI::root()."components/com_virtuemart/shop_image/product/".$productDetailsById[$i]['product_full_image']."\">";
				    	}
				    	else 
				    	{
				    		echo "<img style='border: 2px solid;border-color:silver' width='100px' alt='No image' src=\"".JURI::base()."components/com_qrcode/icons/no-images.jpg\">";
				    	}
				    	echo "</div>";
				    	
			    		echo "<div id='productDetails' style='width:230px;float:left;margin-top:-15px;'><h2>".$productDetailsById[$i]['product_name']."</h2>";
			    		$productDescription = preg_replace('/<font [^>]+\>/i', "", $productDetailsById[$i]['product_desc']);
			    		echo "<h3 style='font-weight:normal;'>".$productDescription."</h3></div>";
			    		echo "<div id='qrcodeImage' style='float:left;margin-right:0px;margin-top:-5px;'><img width='100px' height='100px' src='".JURI::root()."images/qrcode/".$productDetailsById[$i]['qrcode_value']."'></div>";
			    		echo "<div style='clear:both;'>";
			    		echo "<br><br><br>";
				}
			   
		    
		   	?>
	
