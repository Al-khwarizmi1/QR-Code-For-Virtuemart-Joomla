<?php 
/**
 * @name        QR code for Virtuemart
 * @version	1.1: style.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Style page to display available style list for print option.
 */
?>
<style type="text/css">
.icon-48-apptha {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/apptha.gif';?>') 0 0 no-repeat;
  background-size:25px;background-position:20px;
  }
  .icon-48-printer-icon {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/printer-icon.png';?>') 0 0 no-repeat;
  }
  #upload{margin-top:10px;margin-bottom:20px;}
 
</style>

<?php
JHTML::_('behavior.tooltip');
$model = &$this->getModel('qrcode');
$printStyleDetails = $model->getPrintStyle();
?>

<form name="adminForm" id="adminForm" method="post" action="" enctype="multipart/form-data">
	
	<div id="upload">
	    <b><?php echo JText::_('COM_QRCODE_VIEW_STYLE_UPLOAD') ?></b>
	   	<input class="hasTip" title="Upload new style::To upload new print style, please contact support@apptha.com" style="cursor: pointer;" type="file" name="thefile">
	    <input style="cursor: pointer;" type="submit" value="upload">
	</div>
	<table class="adminlist">
		<thead>
		  <tr>
		    <th><?php echo JText::_('COM_QRCODE_VIEW_STYLE_DESIGN');?></th>
		    <th><?php echo JText::_('COM_QRCODE_VIEW_STYLE_NAME');?></th>
		    <th><?php echo JText::_('COM_QRCODE_VIEW_STYLE_STATUS');?></th>
		  </tr>
		</thead>
	  	<?php 
		foreach($printStyleDetails as $key=>$val)
		{
		?>
		
		<tr>
			<td width="30%" align="center"><img width="360px" height="160px" src="<?php echo JURI::base().'components/com_qrcode/icons/'.$val->style_name.'.jpg'?>"/></td>
		    <td align="center"><h3><?php echo $val->style_name;?></h3></td>
		    <td align="center">
		    
			<?php 
			if($val->publish == '0')
			{
				echo "<a href='".JRoute::_('index.php?option=com_qrcode&task=activeStyle&id='.$val->style_id)."'><img src='".JURI::base()."components/com_qrcode/icons/deactive.png'></a>";
			}
			else
			{
				echo '<img src="'.JURI::base().'components/com_qrcode/icons/active.png">';
			}
			?>
			</td>
		</tr>
		
		<?php 
		}
		?>
	</table>
			<input type="hidden" name="view" value="qrcode" />
            <input type="hidden" name="option" value="com_qrcode" />
            <input type="hidden" name="task" value="install" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="controller" value="" />
</form>