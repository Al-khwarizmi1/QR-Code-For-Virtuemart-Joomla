<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.1: install.qrcode.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Custom uninstall script for qrcode component.
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');
error_reporting(0);
// import joomla's filesystem classes
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');
// delete a folder inside your images folder
//JFolder::delete(JPATH_ROOT.DS.'images'.DS.'qrcode');
   
$installer = new JInstaller();
if(JFolder::exists(JPATH_ROOT.DS.'modules'.DS.'mod_qrcode'))
{
	JFolder::delete(JPATH_ROOT.DS.'modules'.DS.'mod_qrcode');
}
if(!JFolder::exists(JPATH_ROOT.DS.'modules'.DS.'mod_qrcode'))
{
$installer->install(JPATH_BASE.DS.'components'.DS.'com_qrcode'.DS.'extensions'.DS.'mod_qrcode');
}


		$db = & JFactory::getDBO();
        $query = 'UPDATE  #__modules '.
                 'SET published=1, ordering=0, position = "left" '.
                 'WHERE module = "mod_qrcode"';
        $db->setQuery($query);
        $db->query();
        
        $query = 'insert into #__modules_menu (moduleid) (SELECT id FROM `#__modules` WHERE module = "mod_qrcode")';
        $db->setQuery($query);
        $db->query();
        
?>