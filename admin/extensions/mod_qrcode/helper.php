<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.1: helper.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Helper file to retrieve data from database.
 */
class modQrcodeHelper
{
    //Function to get all product from virtuemart product table.    
    function getQrcode()
    {
    	$db = & JFactory::getDBO();
		$query = 'select product_id,product_name from #__vm_product';
		$db->setQuery($query);
		$db->query();
		$res = $db->loadObjectList();
        return $res;
    }
}
?>