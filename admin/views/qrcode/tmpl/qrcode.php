<?php

/**
 * @name        QR code for Virtuemart
 * @version	1.1: qrcode.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Default Template for Listing qrcode with corresponding product details.
 */

// No direct access


defined( '_JEXEC' ) or die( 'Restricted access' ); 
JHTML::_( 'behavior.tooltip' );
$getProductDetails = $this->productDetails;
$category = $this->category;
?>

<style type="text/css">
.icon-48-apptha {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/apptha.gif';?>') 0 0 no-repeat;
  background-size:25px;background-position:20px;
  }
  
  
  .icon-48-qrcode_48x48 {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/qrcode_48x48.jpg';?>') 0 0 no-repeat;
  }
  
  .icon-32-mail {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/mail.jpg';?>') 0 0 no-repeat;
  background-size:28px;background-position:3px;
  }
  
  .icon-32-print {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/print.jpg';?>') 0 0 no-repeat;
  background-size:28px;background-position:3px;
  }
  
  .icon-32-qrcode {
  background: url('<?php echo JURI::base().'components/com_qrcode/icons/qrcode_generator.jpg';?>') 0 0 no-repeat;
  background-size:28px;background-position:3px;
  }
</style>

<link href="<?php echo JURI::base(). 'components/com_qrcode/css/qrcode.css'; ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo JURI::base(). 'components/com_qrcode/js/jquery-1.6.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo JURI::base(). 'components/com_qrcode/js/qrcode.js'; ?>"></script>

<script type="text/javascript">
	var COM_QRCODE_SELECT_CHECKBOX = '<?php echo JText::_("COM_QRCODE_SELECT_CHECKBOX"); ?>';
	var cancelRedirect = '<?php echo "index.php?option=com_qrcode&task=cancel";?>';
	var url = '<?php echo JRoute::_("index.php") ?>';

	function searchScript(e,val) {
	    if (e.keyCode == 13) {
	    	document.searchForm.action='<?php echo JURI::base()."index.php?option=com_qrcode&layout=qrcode&searchProduct=";?>'+val;
	    }
	}
</script>

<form name="searchForm" id="searchForm" action="" method="post">
		<div style="float:right;margin-top:20px;">
            <b><?php echo JText::_('COM_QRCODE_VIEW_DEFAULT_SEARCH')?></b>&nbsp;<input size="25px" onclick="form.getElementById('searchByFilter').value='';" onkeypress="searchScript(event,this.value)" title="Enter product name to search" type="text" name="searchByFilter" id="searchByFilter"  value="<?php echo (JRequest::getVar('searchProduct'))?JRequest::getVar('searchProduct'):JText::_('COM_QRCODE_VIEW_DEFAULT_SEARCH_VALUE');?>">
            <a id="searchProduct" href="javascript:void(0);" onClick="window.location.href='<?php echo JURI::base()."index.php?option=com_qrcode&layout=qrcode&searchProduct=";?>'+document.getElementById('searchByFilter').value">Search</a>
            <input id="clearSearch" type="reset" name="reset"  onclick="form.getElementById('searchByFilter').value='';window.location.href='<?php echo JURI::base()."index.php?option=com_qrcode&layout=qrcode"?>'"/>
        </div>
</form>

<form  onsubmit="return createTarget(this);" action="index.php?option=com_qrcode&task=displayQrcode&tmpl=component" method="post" id="adminForm" name="adminForm" target="foobar:width=750,height=450,scrollbars,{(isResizable)?'resizable':''},status">
	
    	<div id="selectList">
    	<div style="float: left;" id="productListQrcode"><b><?php echo JText::_('COM_QRCODE_VIEW_DEFAULT_CATEGORY_LIST'); ?></b>
        
            <?php
                    $options = array();
                    $catId = JRequest::getVar('catId');
                    if($catId)
                    {
                    	$default = $catId;
                    }
                    else
					$default = "";

                    $options[] = JHTML::_('select.option', "0", JText::_('COM_QRCODE_VIEW_DEFAULT_DISPLAY_ALL'));
                    foreach ($category as $key=>$val) {
                        $options[] = JHTML::_('select.option', $val->category_id, $val->category_name);
                    }
                    echo JHTML::_('select.genericlist', $options, 'qrcodeCategory', 'class="inputbox"  ', 'value', 'text', $default);     
            ?>
                        
        </div>
    	
    	<script>
    	var productID = '<?php echo JRequest::getVar('pId'); ?>';
    	getProductByCategory(document.getElementById('qrcodeCategory').value,productID);		
    	</script>
    	
    	<input type="hidden" name="currentURL" id="currentURL" value="<?php echo urlencode(JRequest::getURI()); ?>">
    	<input type="hidden" name="qrcodeURL" id="qrcodeURL" value="<?php echo JURI::base() . "index.php?option=com_qrcode&task=qrcodeGenerate";?>">
    	
        <div id="listProduct" style="float: left;margin-right:10px;"></div>
        <a id="generateButton" href="javascript:void(0);" OnClick="getProductList('<?php echo JURI::base()."index.php?option=com_qrcode&layout=qrcode";?>');"><?php echo JText::_('COM_QRCODE_VIEW_DEFAULT_GO');?></a>
        
        <input type="hidden" id="qrcodePid" value="">
        
        </div>
        
        <div style="clear: both;"></div>
        
        <div id="productListing"></div>
        <div id="qrcodeProduct"></div>
        <div id="qrcodeDisplay">
		<table class="adminlist">
            <thead>
                <tr>
					<th>
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->productDetails); ?>);" />
					</th>
                    <th>
                        <?php echo JText::_('COM_QRCODE_VIEW_DEFAULT_QRCODE'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_QRCODE_VIEW_DEFAULT_PRODUCT_ID'); ?>
                    </th>
                    <th style="text-align: left">
                        <?php echo JText::_('COM_QRCODE_VIEW_DEFAULT_PRODUCT_DETAILS'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_QRCODE_VIEW_DEFAULT_PRODUCT_IMAGE'); ?>
                    </th>
                </tr>
            </thead>
            <?php
			            $model = $this->getModel('qrcode');
			            for($i = 0;$i < count($getProductDetails);$i++)
			            {
			            	$productId[] = $getProductDetails[$i]->product_id;
			            }
			            if(isset($productId))
			            {
			            $productViewPage = $model->printQrcodeDetails($productId);
			            $categoryName = $model->getCategoryName($productId);
			            }
                        $k = 0;
                        for ($i = 0, $n = count($this->productDetails); $i < $n; $i++) 
                        {
                            $qrcodeDetails = & $this->productDetails[$i];
                            $checked = JHTML::_('grid.id', $i, $qrcodeDetails->product_id);
            ?>
                            <tr class="<?php echo 'row' . $k; ?>">
                            <td align="center"><?php echo $checked; ?></td>
                            <td align="center">
                            <?php
								if(!empty($qrcodeDetails->qrcode_value))
								{
	                            	echo '<img width="100px" height="100px" src="'.JURI::root().'images/qrcode/'.$qrcodeDetails->qrcode_value.'">';
								}
								else
								{
									echo JText::_("COM_QRCODE_VIEW_DEFAULT_QRCODE_NOT_GENERATED");
								}
                            ?>
                                
                            </td>
                            <td style="text-align: center"><?php echo $qrcodeDetails->product_id;?></td>
                            <td style="text-align: left">
	                        <?php 
	                        	echo '<b style="font-size:13px;">'.JText::_('COM_QRCODE_VIEW_DEFAULT_PRODUCT_NAME').'</b><a href="'.JURI::root().("index.php?option=com_virtuemart&page=shop.product_details&product_id=$qrcodeDetails->product_id").'" target="_blank"><label style="font-size:13px;color:black;cursor:pointer">'. $qrcodeDetails->product_name.'</label></a></br>'; 
		                        echo '<b style="font-size:13px;">'.JText::_('COM_QRCODE_VIEW_DEFAULT_CATEGORY_NAME').'</b><label style="font-size:13px;color:black;">'.$categoryName[$i][0].'</label>';
		                        
		                        if($qrcodeDetails->product_s_desc != "")
		                        {
		                        	echo '<br><b style="font-size:13px;">'.JText::_('COM_QRCODE_VIEW_DEFAULT_PRODUCT_DESCRIPTION').'</b><label style="font-size:13px;color:black;">'.strip_tags($qrcodeDetails->product_s_desc).'</label>'; 
		                        } 
	                        ?>
                            </td>
                            <td align="center">
                            <?php 
                                $img = $qrcodeDetails->product_thumb_image;
                                if($img)
                                {
                                	echo "<img width='100px' height='100px' alt='No image' src=\"".JURI::root()."components/com_virtuemart/shop_image/product/$img	\">";
                                }
                        		else 
                                {
                                	echo JText::_("COM_QRCODE_VIEW_DEFAULT_PRODUCT_NOT_AVAILABLE");
                                }
                            ?>
                            </td>
                            </tr>
<?php
                            $k = 1 - $k;
                        }
?>
                        
                    </table>
                    </div>
    			
                <input type="hidden" name="view" value="qrcode" />
                <input type="hidden" name="option" value="com_qrcode" />
                <input type="hidden" name="task" value="displayQrcode" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="controller" value="" />
                
</form>
