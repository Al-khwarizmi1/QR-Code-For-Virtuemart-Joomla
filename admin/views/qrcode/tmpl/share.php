<?php
/**
 * @name        Apptha Qrcode
 * @version	1.1: share.php
 * @since       Joomla 1.6
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Share page to send mail for selected products.
 */

defined( '_JEXEC' ) or die();
$cids = JRequest::getVar('val');
$user = JFactory::getUser(); //Get current logged user details.
$fromname = explode("@",$user->email);
?>

<link rel="stylesheet" href="<?php echo JURI::base() . 'components/com_qrcode/css/qrcode.css';?>">
<script type="text/javascript" src="<?php echo JURI::base() . 'components/com_qrcode/js/jquery-1.6.min.js';?>"></script>
<script type="text/javascript" src="<?php echo JURI::base() . 'components/com_qrcode/js/qrcode.js';?>"></script>
<script type="text/javascript" language="javascript">
	var toAddress = "<?php echo JText::_('JERR_EMPTY_RECIPIENT'); ?>";
	var validEmail = "<?php echo JText::_('JERR_VALID_EMAIL'); ?>";
</script>

<?php
	if(JRequest::getCmd('msg'))
	{
?>
	<script type="text/javascript">
	refreshandclose();
	</script>
<?php 
	}
?>

<form action="<?php echo JURI::base()."index.php?option=com_qrcode&task=sendMail&val=".$cids?>" name="adminForm" method="post" >

<div class="send-friend">
    
    <div class="fieldset">
            
            <h2 class="legend"><?php echo JText::_('COM_QRCODE_EMAIL_FRIEND') ?> </h2>
            <ul class="form-list" id="sender_options">
                <li class="fields">
                    <div class="field">
                        <label for="sender_name" class="required"><em>*</em><?php echo JText::_('COM_QRCODE_TO') ?> :</label>
                        <div class="input-box">
                            <input name="to" id="to" title="<?php echo JText::_('COM_QRCODE_TO') ?>"  type="text" class="input-text required-entry">
                            <div id="emailToErr" class="Err"></div>
                        </div>
                    </div>
                    <div class="field">
                       <label for="sender_email" class="required"><?php echo JText::_('COM_QRCODE_SUBJECT') ?></label>
                       <div class="input-box">
                           <input name="subject" id="subject" value="<?php echo $fromname[0].' '.JText::_('COM_QRCODE_SHARE_MAIL_SUBJECT');?>" title="<?php echo JText::_('COM_QRCODE_SUBJECT') ?>" type="text" class="input-text required-entry validate-email">
                           <div id="emailSubjectErr" class="Err"></div>
                       </div>
                    </div>
                </li>
                <li class="wide">
                    <label for="sender_message"><?php echo JText::_('COM_QRCODE_MESSAGE') ?>:</label>
                    <div class="input-box">
                        <textarea name="message" id="message" class="input-text required-entry"  cols="3" rows="3"><?php echo JText::_('COM_QRCODE_SHARE_MAIL_MESSAGE').'&#13;&#13;'.JText::_('COM_QRCODE_SHARE_MAIL_MESSAGE_REMAIN')?></textarea>
                        
                    </div>
                </li>
            </ul>
        </div>
    <button type="submit" name="sendMail" id="sendMail" class="button floatright"><span><span><?php echo JText::_('COM_QRCODE_SEND_MAIL') ?></span></span></button>
</div>
    <input type="hidden" value="option" name="com_qrcode"/>
    <input type="hidden" value="controller" name=""/>
    <input type="hidden" value="task" name="sendMail"/>
</form>
<?php exit(); ?>
