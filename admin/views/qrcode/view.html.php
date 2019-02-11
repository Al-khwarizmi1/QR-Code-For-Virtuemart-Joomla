<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.1: view.html.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Qrcode view to retrieves the data to be displayed and pushes it into the template.
 */
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class QrcodeViewQrcode extends JView
{
	//Default function to display the view page.
    function display($tpl = null)
    {
    	
    	$this->toolbar();
    	$model		= &$this->getModel();
		$productDetails		= &$model->getData();
		$category		= &$model->getCategory();
		
		$getModule = $model->getModule();
		
        $this->assignRef( 'productDetails', $productDetails);
        $this->assignRef( 'category', $category);
		
		$this->assignRef( 'getModule', $getModule);
 		
        parent::display($tpl);
    }
    
    //Function to create toolbar option.
	function toolbar()
	    {
	    	$submenu = "";
	    	if(JRequest::getCmd('layout') == "qrcode")
	    	{
		    	$submenu = "qrcode";
		    	JToolBarHelper::title(   JText::_( 'COM_QRCODE_VIEW_TITLE_LIST' ), 'qrcode_48x48' );
		    	JToolBarHelper :: custom( 'qrcodeGenerate','qrcode','qrcode','Generate');
		    	JToolBarHelper::divider();
		    	JToolBarHelper :: custom( 'print','print','print','Print');
		    	JToolBarHelper :: custom( 'share','mail','mail','Email');
		    	JToolBarHelper::divider();
		    	JToolBarHelper::cancel('cancel', 'Close');
	    	}
	    	else if(JRequest::getCmd('layout') == "settings")
	    	{
		    	$submenu = "settings";
		    	JToolBarHelper::title(   JText::_( 'COM_QRCODE_VIEW_TITLE_SETTINGS' ), 'settings-icon' );
		    	JToolBarHelper::save();
		    	JToolBarHelper::apply();
		    	JToolBarHelper::divider();
		    	JToolBarHelper::cancel('cancel', 'Close');
	    	}
	    	else if(JRequest::getCmd('layout') == "style")
	    	{
	    		$submenu = "style";
	    		JToolBarHelper::title(   JText::_( 'COM_QRCODE_VIEW_TITLE_STYLE' ), 'printer-icon' );
	    		JToolBarHelper::cancel('cancel', 'Close');
	    	}
	    	else if(JRequest::getCmd('layout') == "")
	    	{
	    		$submenu = "default";
	    		JToolBarHelper::title(   JText::_( 'COM_QRCODE_VIEW_TITLE' ), 'apptha' );
	    	}
	    	JSubMenuHelper::addEntry(JText::_('COM_QRCODE_SUBMENU_CONTROL_PANEL'),
			                         'index.php?option=com_qrcode', $submenu == 'default');
	    	JSubMenuHelper::addEntry(JText::_('COM_QRCODE_SUBMENU_GENERATE'),
			                         'index.php?option=com_qrcode&layout=qrcode', $submenu == 'qrcode');
	    	JSubMenuHelper::addEntry(JText::_('COM_QRCODE_SUBMENU_SETTINGS'),
			                         'index.php?option=com_qrcode&layout=settings', $submenu == 'settings');
	    	JSubMenuHelper::addEntry(JText::_('COM_QRCODE_SUBMENU_STYLE'),
			                         'index.php?option=com_qrcode&layout=style', $submenu == 'style');
	    	
	    	
	    	
	    	
	    	
	    }
}