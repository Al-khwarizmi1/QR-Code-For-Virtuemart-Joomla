<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.1: mod_qrcode.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    This is the entry point of qrcode module.
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );
 
$qrcode = modQrcodeHelper::getQrcode( );

if(JRequest::getVar('option') == 'com_virtuemart' && JRequest::getVar('page') == 'shop.product_details')
{
require( JModuleHelper::getLayoutPath( 'mod_qrcode' ) );
}
else
{
	echo "<span style='margin-left:30px;line-height:40px'>No QRcode available</span>";
}
?>