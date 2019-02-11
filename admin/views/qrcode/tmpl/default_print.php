<?php 
/**
 * @name        QR code for Virtuemart
 * @version	1.1: default_print.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Default style page for print view.
 */

require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'printSettings.php';

?>

<style>
	@page { size:<?php echo conf__QRCODE_PRINT_SIZE.' '.conf__QRCODE_PRINT_ORIENTATION?>}
	@media print{
	html,body{height: inherit;}
	#printButton{display:none;}
	body{margin:0px auto;padding:0px;}
	<?php if(conf__QRCODE_PRINT_SIZE == "A4"){?>
	#productImage img{width:35mm !important;}
	#qrcodeImage img{width:35mm !important;height: 35mm !important;}
	#productDetails{width:105mm !important}
	<?php } else if(conf__QRCODE_PRINT_SIZE == "A3"){?>
	#productImage img{width:50mm !important;}
	#qrcodeImage img{width:50mm !important;height: 50mm !important;}
	#productDetails{width:150mm !important}
	<?php } else if(conf__QRCODE_PRINT_SIZE == "A5"){?>
	#productImage img{width:25mm !important;}
	#qrcodeImage img{width:25mm !important;height: 25mm !important;}
	#productDetails{width:60mm !important}
	<?php }?>
	}
</style>

	<div class="alignCenter">
	
		<?php
	
			$printDetails = JRequest::getVar('val');
			$productId = explode(',', $printDetails);
			array_pop($productId);
			$model = $this->getModel('qrcode');
			$productDetailsById = $model->printQrcodeDetails($productId);
		?>
		
			    	<a id="printButton" rel="nofollow" onclick="window.print();" title="<?php echo JText::_('COM_QRCODE_PRINT') ?>" href="javascript:void(0);" ><img alt="<?php echo JText::_('COM_QRCODE_PRINT') ?>" src="<?php echo JURI::root() . "images/M_images/printButton.png"; ?>"></a>
			    	<br><br>
			    	<?php
			    	for($i = 0;$i < count($productDetailsById);$i++)
					{
					    	//echo "<div>";
					    	echo '<div id="qrcodeImage" style="float:left;margin-right:0px;margin-left:0px;"><img width="100px" height="100px" src="'.JURI::root().'images/qrcode/'.$productDetailsById[$i]['qrcode_value'].'"></div>';
				    		echo "<div id='productDetails' style='width:220px;float:left;margin-right:4px;margin-top:-5px;'><h2>".$productDetailsById[$i]['product_name']."</h2>";
				    		$productDescription = preg_replace('/<font [^>]+\>/i', "", $productDetailsById[$i]['product_desc']);
				    		echo "<h3 style='font-weight:normal;'>".$productDescription."</h3></div>";
				    		
				    		echo "<div id='productImage' style='float:left;margin-top:10px;'>";
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
				    		echo "<div style='clear:both;'>";
				    		echo "<br><br>";
					    }
				   
			    
			    ?>
	</div>