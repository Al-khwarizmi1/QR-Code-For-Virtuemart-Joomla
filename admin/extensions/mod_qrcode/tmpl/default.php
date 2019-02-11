<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.1: default.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Default page for qrcode module display.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$productId = JRequest::getVar('product_id');
?>
	<div style="text-align: center;">
	<?php
	foreach($qrcode as $key=>$val)
		{
			if($val->product_id == $productId)
			{
				$qrcodeImage = "";
				$qrcodeImage = $val->product_id;
				if(file_exists(JPATH_ROOT.DS.'images'.DS.'qrcode'.DS.$qrcodeImage.".png"))
				{
					echo "<img width='160px;' src=".JURI::root()."images/qrcode/$qrcodeImage.png>";
					echo "<br><span>".$val->product_name."</span>";
				}
				else
				{
					echo "<span style='line-height:40px'>No QRcode available</span>";
				}
			}
		}
		?>
		</div>