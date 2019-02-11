<?php
/**
 * @name        QR code for Virtuemart
 * @version	1.0: controller.php
 * @since       Joomla 1.5
 * @subpackage	com_QRcode
 * @author      Apptha
 * @copyright   Copyright (C) 2011 Powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @abstract    Controller to generate qrcode and display the view page.
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.controller' );
jimport( 'joomla.filesystem.archive' );
jimport( 'joomla.filesystem.folder' );

class QrcodeController extends JController
{
	    /**
	     * Method to display the view
	     *
	     * @access    public
	    */
		
		//Default function to display the view page.
	    function display()
	    {
	        parent::display();
	    }
	    
	    //Function to generate the qrcode for all the available products.
    	function generateQrcode($selectedProductId)
    	{
    		$ProductId = implode(',',$selectedProductId);
    		$model		= &$this->getModel();
    		$productDetails = $model->getCategoryByProduct($ProductId);
    		$qrcodeImagePath = JPATH_ROOT.DS . 'images'.DS.'qrcode';
            if ( !is_dir($qrcodeImagePath) )
			{
				mkdir($qrcodeImagePath,0777,true);
			}
    		for($i = 0;$i <count($productDetails);$i++)
    		{
    			$qrcodeProductId = $selectedProductId[$i];
    			$qrcodeCategoryId = $productDetails[$i][0];
    			$qrcodeImageContent = urlencode(JURI::root().("index.php?option=com_virtuemart&page=shop.product_details&product_id=$qrcodeProductId&category_id=$qrcodeCategoryId"));
    			$imageName = $qrcodeProductId.'.png';
				$qrcodeImage[] = $qrcodeProductId.'.png';
				// save image in configured image uploads folder 
				$imagePath = $qrcodeImagePath . DS . $imageName;
					 
				$size = "500x500";
				$image_url_qr = "https://chart.googleapis.com/chart?cht=qr&chs=$size&chl=$qrcodeImageContent";
				
				if(function_exists('curl_init'))
				{
				$curl_handle=curl_init();
				curl_setopt($curl_handle,CURLOPT_URL,$image_url_qr);
				curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
				curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
				$returnData = curl_exec($curl_handle);
				curl_close($curl_handle);
				
				$imageContent = $returnData;
						
				$imageLocation = fopen($imagePath , 'w');
				chmod($imagePath , 0777);
				$fp = fwrite($imageLocation, $imageContent);
				fclose($imageLocation);
				$errorMessage = JText::_('COM_QRCODE_CONTROLLER_GENERATE_QRCODE_SUCCESS');
				}
				else 
				{
					$errorMessage = JError::raiseWarning('',"COM_QRCODE_CONTROLLER_ENABLE_CURL");
				}					
    		}
    		if($fp)//If image content write to folder, then store the value in database.
			$model->storeQrcodeDetails($selectedProductId,$qrcodeImage);
			return $errorMessage;
    	}
		    	
    	//Function to cancel the current operation.
		function cancel() 
		{
	        $msg = JText::_('COM_QRCODE_CANCEL');
	        $this->setRedirect(JRoute::_('index.php?option=com_qrcode', false), $msg);
	    }

	    //Function to display the settings page.
		function settings()
		{
			JRequest::setVar( 'view', 'qrcode' );
			JRequest::setVar( 'layout', 'settings' );
			JRequest::setVar( 'hidemainmenu', 0 );
			parent::display();
		}
	    
		//Function to redirect to print or share page.
		function displayQrcode()
	    {
	    	$redirectPage = JRequest::getVar('redirect');
		    $model = $this->getModel('qrcode');
		    $printStyle = $model->getActiveStyle();//Get active style name from the database.
		    $currentStyle = $printStyle->style_name;
		    
		    if($redirectPage == "print")
		    {
				JRequest::setVar( 'view', 'qrcode' );
	        	JRequest::setVar( 'layout', $currentStyle);
	        	JRequest::setVar('hidemainmenu', 0);
	        	parent::display();
		    }
		    else
		    {
		    	JRequest::setVar( 'view', 'qrcode' );
	        	JRequest::setVar( 'layout', 'share');
	        	JRequest::setVar('hidemainmenu', 0);
	        	parent::display();
		    }	        
	    }
    	
		//Function to save the settings details and redirect to list qrcode page.
	    function save()
	    {
	    	$model = $this->getModel('qrcode');
	    	if($model->saveSettings() && $this->createPrintSettingsConfFile())
	    	{
	    		$msg = JText::_('COM_QRCODE_SETTINGS_SAVE');
	    	}
	    	else
	    	{
	    		$msg = JText::_('COM_QRCODE_SETTINGS_SAVE_ERROR');
	    	}
	    	$link = JRoute::_('index.php?option=com_qrcode', false);
	        $this->setRedirect($link, $msg);
	    }
    	
	    //Function to save the settings details and remains in settings page.
		function apply()
	    {
	    	$model = $this->getModel('qrcode');
	    	if($model->saveSettings() && $this->createPrintSettingsConfFile())
	    	{
	    	$msg = JText::_('COM_QRCODE_SETTINGS_SAVE');
	    	}
	    	else
	    	{
	    		$msg = JText::_('COM_QRCODE_SETTINGS_SAVE_ERROR');
	    	}
	    	$link = JRoute::_('index.php?option=com_qrcode&layout=settings', false);
	        $this->setRedirect($link, $msg);
	    	
	    }
    	
		//Function to send the mail.
		function sendMail()
		{
			$model = &$this->getModel('qrcode');
			$productId = JRequest::getVar('val');
			$qrcode = explode(',', $productId);
			array_pop($qrcode);
			$productDetailsById = $model->printQrcodeDetails($qrcode); // Store qrcode details for sending mail.
			$message = "";
			$countItems = 1;
			$message = JRequest::getString("message")."<br><br>";
			for($i = 0;$i < count($productDetailsById);$i++)
			{
				$getProductId = $productDetailsById[$i]['product_id'];
				$message .= "<label style='float:left'>".$countItems.")</label><a style='text-decoration:none' href=".JURI::root().("index.php?option=com_virtuemart&page=shop.product_details&product_id=$getProductId")." target='_blank'><span style='color:#0000cc;font-weight:bold !important;'>".ucfirst($productDetailsById[$i]['product_name'])."</span></a><br><br>";
				$message .= strip_tags($productDetailsById[$i]['product_s_desc'])."<br><br>";
				$img = $productDetailsById[$i]['product_full_image'];
				if(!empty($img))
				{
					$message .= "<a href=".JURI::root().("index.php?option=com_virtuemart&page=shop.product_details&product_id=$getProductId")." target='_blank'><img width='150px' height='150px' alt='No image' src='".JURI::root().'components/com_virtuemart/shop_image/product/'.$productDetailsById[$i]['product_full_image']."'/></a>";
				}
				else
				{
					$message .="<a href=".JURI::root().("index.php?option=com_virtuemart&page=shop.product_details&product_id=$getProductId")." target='_blank'><img style='border: 2px solid;border-color:silver' width='150px' alt='No image' src=\"".JURI::base()."components/com_qrcode/icons/no-images.jpg\"></a>";
				}
				
				$message .= "<img width='150px' height='150px'; src=\"".JURI::root().'images/qrcode/'.$productDetailsById[$i]['qrcode_value']."\"/><br><br>";
				$countItems++;
			}
			
	        $user = JFactory::getUser(); //Get current logged user details.
	        
	        $fromname = explode("@",$user->email);
	
	        $mailer =& JFactory::getMailer();//Initialize getMailer() to send email.
	        $to = JRequest::getVar("to");
	        $subject = JRequest::getString("subject");
	        $mailer->setSender(array($user->email,  ucfirst($fromname[0]) ));
	        $mailer->addRecipient($to);
	        $mailer->setSubject($subject);
	        $mailer->isHTML(true);
	        $mailer->Encoding = 'base64';
	        $mailer->setBody($message);
	        $send =& $mailer->Send();
	        if ( $send !== true ) 
		    {
		    	$msg =  JText::_('COM_QRCODE_MAIL_ERROR');
		    } 
	        else 
		    {
		        $msg = JText::_('COM_QRCODE_MAIL_SUCCESS');
		    }
	        $link = JURI::base().'index.php?option=com_qrcode&layout=share&msg='.$msg;
	        $this->setRedirect($link, $msg);
	    }
	    
	    //Function to display the product drop-down list for selected category.
	    function displayCategory()
	    {
	    	$productId = JRequest::getVar('product_id');
	    	
	    	$model = $this->getModel('qrcode');
	    	$productList = $model->getProductByCategory();//Store product details based on category.
	    	ob_clean();
	    	
	    	echo "<b>".JText::_('COM_QRCODE_VIEW_DEFAULT_PRODUCT_LIST')."</b>";
                    
	    			$options = array();
                    $default = "";
                    if($productId)
                    {
                    	$default = $productId;
                    }
                    $options[] = JHTML::_('select.option', "0", JText::_('COM_QRCODE_VIEW_DEFAULT_DISPLAY_ALL'));
                    foreach ($productList as $key=>$val) {
                        $options[] = JHTML::_('select.option', $val['product_id'], $val['product_name']);
                    }
                    echo JHTML::_('select.genericlist', $options, 'qrcode', 'onchange=getProductId(this.value)','value',  'text', $default);    
            exit();
	    }
		
	    //Function to get generated QR code and redirect to list QR code page
	    function qrcodeGenerate()
	    {
	    	$selectedValue  = JRequest::getVar('selectedValue');
	    	$selectedProductId = explode(",", $selectedValue);
	    	array_pop($selectedProductId);
	    	$qrcodeId = implode(',',$selectedProductId);
	    	$qrcodeGenerateResult = $this->generateQrcode($selectedProductId);//function to generate qrcode for product.
	    	$path = urldecode(JRequest::getVar('redirectPath'));
	        $this->setRedirect($path,$qrcodeGenerateResult);
	    }
	    
	    // Function to activate the style for print.
		function activeStyle()
	    {
	    	$styleId  = JRequest::getInt('id');
	    	$model = &$this->getModel('qrcode');
	    	if($model->changeStyle($styleId))
	    	{
	    		$msg = JText::_('COM_QRCODE_CONTROLLER_STYLE_ACTIVATE');
	    	}
	    	else 
	    	{
	    		$msg = JText::_('COM_QRCODE_CONTROLLER_STYLE_NOT_ACTIVATE');
	    	}	
	    	$link = JURI::base().'index.php?option=com_qrcode&layout=style';
	        $this->setRedirect($link,$msg);
	    }
	    
		//function to create file with site information.
	    function createPrintSettingsConfFile() {
	    	
	        $fileContent = "<?php \n";
	        $fileContent .= " /** \n";
	        $fileContent .= " * @name        QR code for Virtuemart \n";
	        $fileContent .= " * @version	1.0: crop.php 2011-06-06 \n";
	        $fileContent .= " * @since       Joomla 1.5 \n";
	        $fileContent .= " * @subpackage	com_qrcode \n";
	        $fileContent .= " * @author      contus support \n";
	        $fileContent .= " * @copyright   Copyright (C) 2011 Contus Support \n";
	        $fileContent .= " * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html \n";
	        $fileContent .= " * @abstract     Constants for print setting. \n";
	        $fileContent .= " **/ \n";
	        $fileContent .= "define('conf__QRCODE_PRINT_SIZE','" . JRequest::getVar("size") . "');\n ";
	        $fileContent .= "define('conf__QRCODE_PRINT_ORIENTATION','" . JRequest::getVar("orientation") . "');\n ";
	        $fileContent .= "?>";
	        $path = JPATH_COMPONENT_ADMINISTRATOR . DS . "helpers" . DS . "printSettings.php";
	        $fp = fopen($path, "w");
	        if ($fp) 
	        {
	            fwrite($fp, $fileContent);
	            fclose($fp);
	            return true;
	        }
	        else
	            return false;
	    }
	    
		// Fcuntion to upload the package for print style
		function install()
	    {
	        $result = $this->_getPackageFromUpload();
	        if(empty($result))
	        {
	            $this->setRedirect('index.php?option=com_qrcode&layout=style', $result);
	        }
	        else
	        {
	            $this->setRedirect('index.php?option=com_qrcode&layout=style', $result);
	        }
    	}

	    /**
	     * Works out an installation package from a HTTP upload
	     *
	     *
	     * @return package definition or false on failure
	     */
	    protected function _getPackageFromUpload()
	    {
            // Get the uploaded file information
            
            $userfile = JRequest::getVar('thefile', "", 'files', 'array');
            
            // Make sure that file uploads are enabled in php
            if (!(bool) ini_get('file_uploads')) 
            {
                JError::raiseWarning('', JText::_('COM_QRCODE_CONTROLLER_FILE_UPLOAD_ENABLE_SERVER'));
                return false;
            }
            
            // Make sure that zlib is loaded so that the package can be unpacked
            if (!extension_loaded('zlib')) 
            {
                JError::raiseWarning('', JText::_('COM_QRCODE_CONTROLLER_ZLIB_NOT_INSTALLED'));
                return false;
            }
            
            // If there is no uploaded file, we have a problem...
            if (!is_array($userfile)) 
            {
                JError::raiseWarning('',JText::_('COM_QRCODE_CONTROLLER_NO_FILE_SELECT'));
                return false;
            }
            
            //Check if file exists when uploaded.
	    	if (empty($userfile['name'])) 
	    	{
                JError::raiseWarning('',JText::_('COM_QRCODE_CONTROLLER_NO_FILE_SELECT'));
                return false;
            }
            
            // Check if there was a problem uploading the file.
            if ($userfile['error'] || $userfile['size'] < 1) 
            {
                JError::raiseWarning('',JText::_('COM_QRCODE_CONTROLLER_INSTALLATION_ERROR'));
                return false;
            }
            
            // Build the appropriate paths
            $config = JFactory::getConfig();

            // Move uploaded file
            jimport( 'joomla.filesystem.file' );
            if (preg_match("/.zip/", $_FILES["thefile"]["name"]))
            {
	            $fileName = $_FILES["thefile"]["name"];
	            $getFileName = explode(".", $fileName);
	            $dir = JPATH_COMPONENT_ADMINISTRATOR . DS . 'views'.DS.'qrcode'.DS.'tmpl';
	            
	            $tmpSrc = $userfile['tmp_name'];
	            $tmpDest = $dir . DS . $userfile['name'];
	            $fileName = $userfile['name'];
	            $uploaded = JFile::upload($tmpSrc, $tmpDest);
				if(file_exists($dir.DS.$getFileName[0].'.php'))
				{
					unlink($dir.DS.$getFileName[0]. ".zip");//Delete zip file after install.
					JError::raiseWarning('', JText::_('COM_QRCODE_CONTROLLER_STYLE_EXIST'));
	            	return false;
				}
				else 
				{
		            // Unpack the downloaded package file
		            $package = $this->unpacking($tmpDest, $fileName);
		            return $package;
				}
			}
            else
            {
	            JError::raiseWarning('', JText::_('COM_QRCODE_CONTROLLER_INVALID_ARCHIEVE_FILE'));
	            return false;
			}
    	}
		
    	// Function to unpack the uploaded package.
    	protected function unpacking($tmpDest, $fileName) 
    	{
	        $db = & JFactory::getDBO();
	        $query = $db->getQuery(true);
	        $archiveName = $tmpDest;
	        $getFileName = explode(".", $fileName);
	        $styleName = $getFileName[0];
	        $extractDir = JPath::clean($tmpDest);
	        
	        $directoryPath = JPATH_COMPONENT_ADMINISTRATOR . DS . 'views'.DS.'qrcode'.DS.'tmpl';
	        
	        $archiveName = JPath::clean($archiveName);
	        $storeStyle = "";
	        $uploadPackage = JArchive::extract($archiveName, $directoryPath);//Function to extract the uploaded style package.
	        if ($uploadPackage) 
	        {
	            unlink($directoryPath . DS . $getFileName[0] . ".zip");
	        }
        
        	if ($uploadPackage === true && file_exists($directoryPath . DS . $getFileName[0].'.php'))
        	{
	        	$imgLocation = $directoryPath . DS . $getFileName[0].'.jpg';
	        	$newImgLocation = JPATH_COMPONENT_ADMINISTRATOR.DS.'icons'.DS.$getFileName[0].'.jpg';
	        	if(file_exists($imgLocation))//Move image of uploaded style to icon folder.
	        	{
		        	if (copy($imgLocation, $newImgLocation))
		        	{
		        		unlink($imgLocation);
		        	}
	        	}
	            $storeStyle = JArchive::extract($archiveName, $directoryPath);//Function to extract the uploaded style package.
	            
	            $model = $this->getModel('qrcode');
	            $styleNameList = $model->getStyleList($getFileName[0]);//check wheather the uploaded style is available in database
	            if(empty($styleNameList))
	            {
	            	$model->insertStyle($getFileName[0]);//Insert the uploaded style name to the database
	            }
	            if(!empty($styleNameList) && $styleNameList==true)
	            {
                     JError::raiseWarning('', JText::_('COM_QRCODE_CONTROLLER_STYLE_EXIST'));
                     return false;
	            }
	            
                $msg =strtoupper($styleName) . "\n" .  JText::_( 'COM_QRCODE_CONTROLLER_STYLE_UPLOAD_SUCCESSFULLY' );
                return $msg;
            }
         else
         {
         	$unlinkFolder = $directoryPath . DS . $getFileName[0];
         	if(file_exists($unlinkFolder))
         	JFolder::delete($unlinkFolder);
            JError::raiseWarning('', JText::_('COM_QRCODE_CONTROLLER_PACKAGE_NOT_VALID'));
            return false;
        }
    }
}
?>