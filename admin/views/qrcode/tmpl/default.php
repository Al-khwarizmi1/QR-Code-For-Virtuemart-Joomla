<?php

/**
 * @name        QR code for Virtuemart
 * @version	1.1: default.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Control panel for QR code component.
 */

/* No direct acesss */

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.pane' );
?>
<style>
.icon-48-apptha {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/apptha.gif';?>') 0 0 no-repeat;
  background-size:25px;background-position:20px;
  }
</style>
<?php 
	function quickiconButton( $link, $image, $text )
	  	{
	           	global $mainframe;
	          	$lang        =& JFactory::getLanguage();
	         	?>
	         	<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
	            <div class="icon">
	            <a href="<?php echo $link; ?>">
	            <?php echo JHTML::_('image.site',  $image, 'components/com_qrcode/icons/', NULL, NULL, $text ); ?>
	            <span><?php echo $text; ?></span></a>
	            </div>
	          	</div>
	  	<?php
		}
      	?>
<div class="adminform">
<div class="cpanel-left">
        
            <div id="cpanel">
                <?php
                $link = 'index.php?option=com_qrcode&layout=qrcode';
                quickIconButton( $link, 'qrcode_48x48.jpg', JText::_( 'COM_QRCODE_SUBMENU_GENERATE' ) );
                
                $link = 'index.php?option=com_qrcode&layout=settings';
                quickIconButton( $link, 'settings-icon.png', JText::_( 'COM_QRCODE_SUBMENU_SETTINGS' ) );

                $link = 'index.php?option=com_qrcode&layout=style';
                quickIconButton( $link, 'printer-icon.png', JText::_( 'COM_QRCODE_SUBMENU_STYLE' ) );
                ?>

                <div style="clear:both">&nbsp;</div>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
	    </div>
        </div>

	<input type="hidden" name="option" value="com_qrcode" />
	<input type="hidden" name="view" value="qrcode" />
</div>
