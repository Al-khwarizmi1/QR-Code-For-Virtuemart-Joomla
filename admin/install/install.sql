DELETE FROM `#__menu` WHERE link LIKE '%com_qrcode%';


--
-- Table structure for table `#__vm_qrcode`
--

CREATE TABLE IF NOT EXISTS `#__vm_qrcode` (
  `qrcode_id` int(10) NOT NULL AUTO_INCREMENT,
  `qrcode_product_id` int(10) NOT NULL,
  `qrcode_value` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`qrcode_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- Table structure for table `#__vm_qrcode_printstyle`
--

CREATE TABLE IF NOT EXISTS `#__vm_qrcode_printstyle` (
  `style_id` int(11) NOT NULL AUTO_INCREMENT,
  `style_name` varchar(100) NOT NULL,
  `publish` int(1) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`style_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `#__vm_qrcode_printstyle`
--

INSERT INTO `#__vm_qrcode_printstyle` (`style_id`, `style_name`, `publish`, `created_date`) VALUES
(1, 'style1', 0, '0000-00-00 00:00:00'),
(2, 'default_print', 1, '0000-00-00 00:00:00') ON DUPLICATE KEY UPDATE style_id=style_id ;
