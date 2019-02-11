<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.1: qrcode.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Model for retrieve qrcode values and related product details from the database.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.helper' );
jimport( 'joomla.application.component.model' );

class QrcodeModelQrcode extends JModel
{
		//Function to retrieve the category id based on product id.
	    function getCategoryByProduct($productId)
	    {
	    	$db = & JFactory::getDBO();
	    	$query = 'select category.category_id from #__vm_product_category_xref as category '
	    			.'inner join #__vm_product as product on product.product_id = category.product_id '
	    			.'where find_in_set(category.product_id,"'.$productId.'")';
	    	$db->setQuery($query);
			$db->query();
			$row = $db->loadRowList();
			return $row; 
	    }
	    
 		//Function to retrieve all the product details from the database.
		function &getData()
		{
		    // Load the data
		    if (empty( $this->_data )) 
		    {
		    	$db = & JFactory::getDBO();
		    	$searchProduct = JRequest::getVar('searchProduct');
			    $catId = JRequest::getVar('catId');
		    	$productSt = "";
		    	$searchResult = "";
		    	$pId = JRequest::getVar('pId');
		    	if($pId)
		    	{
		    		$productSt = ' AND product.product_id = '.$pId;
		    	}
		    	if($searchProduct)
				{
					$searchResult = ' AND product.product_name like "%'.$searchProduct.'%"';
				}
				if($catId > 0 && !$pId)
				{
					$query = 'select product.product_id,product.product_name,product.product_s_desc,product.product_thumb_image,qrcode.qrcode_value from #__vm_product as product '
							.'inner join #__vm_product_category_xref as category on category.product_id = product.product_id '
							.'left join #__vm_qrcode AS qrcode ON (product.product_id = qrcode.qrcode_product_id) where product.product_publish = "Y" AND category.category_id = '.$catId.' order by product.product_id asc';
				}
				else
				{
			        $query = ' SELECT product.product_id,product.product_name,product.product_s_desc,product.product_thumb_image,qrcode.qrcode_value FROM #__vm_product as product ' 
			        .' left join #__vm_qrcode as qrcode on product.product_id = qrcode.qrcode_product_id '
			        .' WHERE product.product_publish = "Y" AND product.product_parent_id = 0 '.$productSt.$searchResult.' order by product.product_id asc';
			    }
				$this->_db->setQuery( $query );
			    $this->_data = $this->_db->loadObjectList();
		    }
		    return $this->_data;
		}
		
		//Function to get QR code details for print.
		function printQrcodeDetails($productId){
			$productId = implode(',', $productId);
			$db = & JFactory::getDBO();
			$query = 'select distinct(category.product_id), product.product_name,product.product_id,product.product_s_desc,product.product_desc,product.product_full_image,qrcode.qrcode_value from #__vm_product as product '
					.'inner join #__vm_product_category_xref as category on category.product_id = product.product_id '
					.'left join #__vm_qrcode AS qrcode on product.product_id = qrcode.qrcode_product_id '
					.'where find_in_set(product.product_id,"'.$productId.'")';
			$db->setQuery($query);
			$db->query();
			$result = $db->loadAssocList();
			return $result;
		}
		
		//Function to store the qrcode details into the database.
		function storeQrcodeDetails($productId,$qrcodeImage) 
		{
			$checkProduct = implode(',', $productId);
			$insertValues = '';
			$insertId = array();
			$updateId = array();
			$splitArray = array();
			$db = & JFactory::getDBO();
			$query = 'select GROUP_CONCAT( qrcode_product_id ) AS product_id  from #__vm_qrcode where qrcode_product_id IN ('.$checkProduct.') order by qrcode_product_id asc';
			$db->setQuery($query);
			$db->query();
			$result = $db->loadRow();
			$resultId = explode(',', $result[0]);
			
			for($i=0;$i<count($productId);$i++)
			{
			  if(isset($resultId)){	  	
				if(in_array("$productId[$i]", $resultId))
				 $updateId[] = $productId[$i];
				else
				  $insertId[] = $productId[$i];
			  }else{
			   $insertId[] = $productId[$i];
			  }			
			}
			$updateProductId = implode(',', $updateId);
			
			 $query = 'UPDATE #__vm_qrcode '
					.'SET modified_date = CASE qrcode_product_id '; 
			for($i=0;$i<count($updateId);$i++)
			{
				$query .= 'WHEN '.$updateId[$i].' THEN CURRENT_TIMESTAMP ';
			}
			$query .= 'END where qrcode_product_id  IN ('.$updateProductId.')';
			
			$db->setQuery($query);
		    $db->query();
			
			$query = 'INSERT INTO  #__vm_qrcode (`qrcode_product_id` ,`qrcode_value`) VALUES ';
			
			for($i=0;$i<count($insertId);$i++)
			{
				$insertValues .= '("'.$insertId[$i].'","'.$insertId[$i].'.png"),';
			}
			$insertValues = substr_replace($insertValues, ';', -1);
			$query = $query.$insertValues;
		    $db->setQuery($query);
		    $db->query();
		    return true;
	    }
	    
	    //Function to get category name based on product id.
	    function getCategoryName($productId)
	    {
	    	$productId = implode(',',$productId);
	    	$db = & JFactory::getDBO();
	    	$query = 'select category.category_name from #__vm_category as category inner join #__vm_product_category_xref as productCategory on productCategory.category_id = category.category_id '
	    			.'where find_in_set(productCategory.product_id,"'.$productId.'") order by productCategory.product_id';
	    	$db->setQuery($query);
			$db->query();
			$row = $db->loadRowList();
			return $row;
		}
	    
	    //Function to save the changes in settings layout.
		function saveSettings()
	    {
	    	$db = & JFactory::getDBO();
	    	$enableQrcode = JRequest::getVar('enable_qrcode');
	    	if($enableQrcode == "1")
	    	{
	    		$query = "UPDATE  #__modules SET published = '1' WHERE module = 'mod_qrcode'";
	    	}
	    	else 
	    	{
	    		$query = "UPDATE  #__modules SET published = '0' WHERE module = 'mod_qrcode'";
	    	}
	    	
	    	$db->setQuery($query);
			if($db->query())
			{
				return true;
			}
			else
			{
				return false;
			}
	    }
	    
	    //Function to get the publish status of QR code module
	    function getModule()
	    {
	    	$db = & JFactory::getDBO();
	    	$query = "select published 	from #__modules where module = 'mod_qrcode'";
	    	$db->setQuery($query);
			$db->query();
			$row = $db->loadAssoc();
			return $row;
	    }
	    
	    //Function to get list of categories from virtuemart category table.
	    function getCategory()
	    {
	    	$db = & JFactory::getDBO();
			$query = 'select category_id,category_name from #__vm_category';
			$db->setQuery($query);
			$db->query();
			$result = $db->loadObjectList();
			return $result;
	    }
	    
	    //Function to get product list based on category id.
	    function getProductByCategory()
	    {
	    	$categoryId = JRequest::getVar('category_id');
	    	$db = & JFactory::getDBO();
	    	$query = 'select product.product_id,product.product_name from #__vm_product as product '
	    			.'inner join #__vm_product_category_xref as category on product.product_id = category.product_id '
	    			.'where product.product_publish = "Y" AND category.category_id = '.$categoryId;
	    	$db->setQuery($query);
			$db->query();
			$row = $db->loadAssocList();
			return $row;
	    }
	       
	    // Function to get the print style from the database
		function getPrintStyle()
	    {
	    	$db = & JFactory::getDBO();
	    	$query = 'select style_id,style_name,publish from #__vm_qrcode_printstyle order by style_name asc';
			$db->setQuery($query);
			$res = $db->loadObjectList();
			return $res;
	    }
	    
	    //Function to get the activated style name.
		function getActiveStyle()
	    {
	    	$db = & JFactory::getDBO();
	    	$query = 'select style_name from #__vm_qrcode_printstyle where publish = 1';
			$db->setQuery($query);
			$res = $db->loadObject();
			return $res;
	    }
	    
	    // Function to update the status of all the styles in qrcode_printstyle table.
	    function changeStyle($styleId)
	    {
	    	$db = & JFactory::getDBO();
	    	$query = 'UPDATE  #__vm_qrcode_printstyle SET publish = "1" WHERE style_id = "'.$styleId.'"';
			$db->setQuery($query);
			$db->query();
			$query = 'UPDATE  #__vm_qrcode_printstyle SET publish = "0" WHERE style_id != "'.$styleId.'"';
			$db->setQuery($query);
			if($db->query())
			{
				return true;
			}
			else 
			{
				return false;
			}
	    	
	    }
	    
	    //Function to check the style in database when upload new style.
		function getStyleList($name)
	    {
	        $db = & JFactory::getDBO();
	        $query = " SELECT style_name "
	                . "FROM #__vm_qrcode_printstyle Where style_name='$name'";
	        $db->setQuery($query);
	        $activeStyleData = $db->loadRowList();
	        if($activeStyleData)
	            return true;
	        else
	            return false;
	    }
    	
	    //Function to insert new style into database.
	    function insertStyle($name)
	    {
	    	$db = & JFactory::getDBO();
	    	$query = 'INSERT INTO #__vm_qrcode_printstyle '
			                    . '(`style_name`,`publish`,`created_date`) '
			                    . 'VALUES ("' . $name . '","0",CURRENT_TIMESTAMP ) ';
			$db->setQuery($query);
			$db->query();
	    }
}
?>
