<?php

/**
 * @name        QR code for Virtuemart
 * @version	1.1: settings.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Settings page to change the settings for display of qrcode in front-end.
 */

// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );

$getModule = $this->getModule;
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'printSettings.php';
?>
<style>
	.icon-48-apptha {
  	background: url('<?php echo JURI::base().'components/com_qrcode/icons/apptha.gif';?>') 0 0 no-repeat;
  	background-size:25px;background-position:20px;
  	}
  	
  	.icon-48-settings-icon {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/settings-icon.png';?>') 0 0 no-repeat;
  }
  	
  	#printSize{margin-left:50px}
  	#printOrientation{margin-left:50px}
  	#printSettings{margin:10px;}
</style>
<link href="<?php echo JURI::base(). 'components/com_qrcode/css/qrcode.css'; ?>" rel="stylesheet">

	<form action="index.php" method="post" name="adminForm">
    				
    	
    		<fieldset>
            <legend><?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_MODULE_DISPLAY');?></legend>
            <div id="settingsQrcode">
	    		<b> <?php echo JText::_('COM_QRCODE_ENABLE_QRCODE'); ?> </b>
	    			
	            <input type="radio" id="enable_qrcode" name="enable_qrcode" checked="checked" value="1" <?php echo ($getModule['published'] == '1')?"checked=checked":""?>>
	                
	            <span> <?php echo JText::_('COM_QRCODE_ENABLE_QRCODE_YES'); ?> </span>
	
	            <input type="radio" id="enable_qrcode" name="enable_qrcode" value="2" <?php echo ($getModule['published'] == '0')?"checked=checked":""?>>
	                
	            <span> <?php echo JText::_('COM_QRCODE_ENABLE_QRCODE_NO'); ?> </span>
            </div>
            </fieldset>
            
            <fieldset>
	            <legend><?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_PRINT_SETTINGS');?></legend>
	            <div id="printSettings">
		            <b><?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_PAGE_SIZE');?></b><br>
		            <div id="printSize">
			            <input type="radio" name="size" id="size" value="A3" <?php echo (conf__QRCODE_PRINT_SIZE == 'A3')?"checked=checked":"";?>>
			            <?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_PRINT_PAGE_SIZE_A3');?>
			            <br>
			            <input type="radio" name="size" id="size" value="A4" <?php echo (conf__QRCODE_PRINT_SIZE == 'A4')?"checked=checked":"";?>>
						<?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_PRINT_PAGE_SIZE_A4');?>
			            <br>
			            <input type="radio" name="size" id="size" value="A5" <?php echo (conf__QRCODE_PRINT_SIZE == 'A5')?"checked=checked":"";?>>
						<?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_PRINT_PAGE_SIZE_A5');?>
		            </div>
		            <br>
		            <b><?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_PAGE_ORIENTATION');?></b><br>
		            <div id="printOrientation"><input type="radio" name="orientation" id="size" checked="checked" value="portrait" <?php echo (conf__QRCODE_PRINT_ORIENTATION == 'portrait')?"checked=checked":"";?>>
			            <?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_PORTRAIT'); ?>
			            <br>
			            <input type="radio" name="orientation" id="size" value="landscape" <?php echo (conf__QRCODE_PRINT_ORIENTATION == 'landscape')?"checked=checked":"";?>>
						<?php echo JText::_('COM_QRCODE_VIEW_SETTINGS_LANDSCAPE'); ?>
		            </div>
	            </div>
            </fieldset>
        
        <input type="hidden" name="view" value="qrcode" />
        <input type="hidden" name="option" value="com_qrcode" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="controller" value="" />
                
	</form>
