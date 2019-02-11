<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.1: uninstall.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Custom uninstall script for qrcode component.
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');

// import joomla's filesystem classes
jimport('joomla.filesystem.folder');

if(JFolder::exists(JPATH_ROOT.DS.'images'.DS.'qrcode'))
{
	
// delete a folder inside your images folder
JFolder::delete(JPATH_ROOT.DS.'images'.DS.'qrcode');
}   

if(JFolder::exists(JPATH_ROOT.DS.'modules'.DS.'mod_qrcode'))
{
	
// delete a folder inside your module folder
JFolder::delete(JPATH_ROOT.DS.'modules'.DS.'mod_qrcode');
}  

		$db = & JFactory::getDBO();
		$query = "DELETE FROM #__modules WHERE module = 'mod_qrcode' limit 1";
        $db->setQuery($query);
        $db->query();

?>