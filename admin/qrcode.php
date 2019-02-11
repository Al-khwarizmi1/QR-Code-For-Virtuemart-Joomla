<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.0: qrcode.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    This file is the entry point to our component.
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Require the base controller
 
require_once( JPATH_COMPONENT.DS.'controller.php' );
 
// Require specific controller if requested
if($controller = JRequest::getWord('controller')) 
{
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) 
    {
    	require_once $path;
    } 
    else 
    {
        $controller = '';
    }
}
 
// Create the controller
$classname    = 'QrcodeController'.$controller;
$controller   = new $classname( );
 
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();