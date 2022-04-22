/*
SQLyog Community v8.55 
MySQL - 5.1.36-community-log : Database - abc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`abc` /*!40100 DEFAULT CHARACTER SET latin1 */;

/*Table structure for table `cashbook` */

DROP TABLE IF EXISTS `cashbook`;

CREATE TABLE `cashbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `category_name` varchar(55) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_type` enum('income','expense') DEFAULT 'expense',
  `description` text,
  `store_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `active` tinyint(1) DEFAULT '1',
  `store_id` int(11) DEFAULT NULL,
  `expense` tinyint(1) DEFAULT '0',
  `income` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `employees` */

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(55) DEFAULT NULL,
  `address` text,
  `joining_date` date DEFAULT NULL,
  `last_working_date` date DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `invoices` */

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `details` text,
  `store_id` int(11) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `dd_no` varchar(55) DEFAULT NULL,
  `dd_amount` decimal(12,2) DEFAULT NULL,
  `dd_purchase` decimal(12,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `storeIdInvoicesIndex` (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=126 DEFAULT CHARSET=latin1;

/*Table structure for table `product_categories` */

DROP TABLE IF EXISTS `product_categories`;

CREATE TABLE `product_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `active` tinyint(1) DEFAULT '1',
  `store_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shortcut_key` varchar(55) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `product_code` varchar(55) DEFAULT NULL,
  `store_id` int(11) NOT NULL,
  `product_category_id` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  `sort` smallint(6) DEFAULT NULL,
  `box_buying_price` decimal(10,2) DEFAULT NULL,
  `box_selling_price` decimal(10,2) DEFAULT NULL,
  `box_qty` int(10) unsigned DEFAULT NULL,
  `unit_buying_price` decimal(10,2) DEFAULT NULL,
  `unit_selling_price` decimal(10,2) DEFAULT NULL,
  `special_margin` decimal(10,2) DEFAULT '0.00',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `storeIDProductsIndex` (`store_id`),
  KEY `productCategoryIdProductsIndex` (`product_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1714 DEFAULT CHARSET=latin1;

/*Table structure for table `purchases` */

DROP TABLE IF EXISTS `purchases`;

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(55) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_category_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `special_margin` decimal(10,2) DEFAULT '0.00',
  `box_buying_price` decimal(12,2) DEFAULT NULL,
  `box_qty` int(10) unsigned DEFAULT NULL,
  `total_special_margin` decimal(10,2) DEFAULT '0.00',
  `total_amount` decimal(12,2) DEFAULT NULL,
  `units_in_box` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_units` int(10) unsigned DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `product_name` varchar(55) DEFAULT NULL,
  `category_name` varchar(55) DEFAULT NULL,
  `store_name` varchar(55) DEFAULT NULL,
  `invoice_name` varchar(55) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productIDIndex` (`product_id`),
  KEY `storeIdPurchasesIndex` (`store_id`),
  KEY `invoiceIdPurchasesIndex` (`invoice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4856 DEFAULT CHARSET=latin1;

/*Table structure for table `salaries` */

DROP TABLE IF EXISTS `salaries`;

CREATE TABLE `salaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `employee_name` varchar(55) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `remarks` text,
  `store_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `sales` */

DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(55) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_category_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_units` int(10) unsigned DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `product_name` varchar(55) DEFAULT NULL,
  `category_name` varchar(55) DEFAULT NULL,
  `store_name` varchar(55) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `closing_stock_qty` int(11) DEFAULT NULL,
  `reference` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productIDSalesIndex` (`product_id`),
  KEY `storeIdSalesIndex` (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3345 DEFAULT CHARSET=latin1;

/*Table structure for table `stores` */

DROP TABLE IF EXISTS `stores`;

CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `suppliers` */

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `product_purchase_report` */

DROP TABLE IF EXISTS `product_purchase_report`;

/*!50001 DROP VIEW IF EXISTS `product_purchase_report` */;
/*!50001 DROP TABLE IF EXISTS `product_purchase_report` */;

/*!50001 CREATE TABLE  `product_purchase_report`(
 `id` bigint(20) ,
 `product_name` varchar(255) ,
 `product_category_id` int(11) ,
 `store_id` int(11) ,
 `purchase_qty` decimal(33,0) ,
 `purchase_amount` decimal(34,2) 
)*/;

/*Table structure for table `product_sale_report` */

DROP TABLE IF EXISTS `product_sale_report`;

/*!50001 DROP VIEW IF EXISTS `product_sale_report` */;
/*!50001 DROP TABLE IF EXISTS `product_sale_report` */;

/*!50001 CREATE TABLE  `product_sale_report`(
 `id` bigint(20) ,
 `product_name` varchar(255) ,
 `product_category_id` int(11) ,
 `store_id` int(11) ,
 `sale_qty` decimal(33,0) ,
 `sale_amount` decimal(34,2) 
)*/;

/*Table structure for table `product_stock_report` */

DROP TABLE IF EXISTS `product_stock_report`;

/*!50001 DROP VIEW IF EXISTS `product_stock_report` */;
/*!50001 DROP TABLE IF EXISTS `product_stock_report` */;

/*!50001 CREATE TABLE  `product_stock_report`(
 `product_id` bigint(20) ,
 `product_name` varchar(255) ,
 `category_id` int(10) unsigned ,
 `category_name` varchar(255) ,
 `store_id` int(11) ,
 `purchase_qty` decimal(33,0) ,
 `purchase_amount` decimal(34,2) ,
 `sale_qty` decimal(33,0) ,
 `sale_amount` decimal(34,2) ,
 `balance_qty` decimal(34,0) ,
 `profit_amount` decimal(35,2) 
)*/;

/*View structure for view product_purchase_report */

/*!50001 DROP TABLE IF EXISTS `product_purchase_report` */;
/*!50001 DROP VIEW IF EXISTS `product_purchase_report` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_purchase_report` AS select `pr`.`id` AS `id`,`pr`.`name` AS `product_name`,`pr`.`product_category_id` AS `product_category_id`,`pr`.`store_id` AS `store_id`,coalesce(sum(`pu`.`total_units`),0) AS `purchase_qty`,coalesce(sum(`pu`.`total_amount`),0) AS `purchase_amount` from (`products` `pr` left join `purchases` `pu` on((`pr`.`id` = `pu`.`product_id`))) group by `pr`.`id` */;

/*View structure for view product_sale_report */

/*!50001 DROP TABLE IF EXISTS `product_sale_report` */;
/*!50001 DROP VIEW IF EXISTS `product_sale_report` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_sale_report` AS select `pr`.`id` AS `id`,`pr`.`name` AS `product_name`,`pr`.`product_category_id` AS `product_category_id`,`pr`.`store_id` AS `store_id`,coalesce(sum(`s`.`total_units`),0) AS `sale_qty`,coalesce(sum(`s`.`total_amount`),0) AS `sale_amount` from (`products` `pr` left join `sales` `s` on((`pr`.`id` = `s`.`product_id`))) group by `pr`.`id` */;

/*View structure for view product_stock_report */

/*!50001 DROP TABLE IF EXISTS `product_stock_report` */;
/*!50001 DROP VIEW IF EXISTS `product_stock_report` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_stock_report` AS select `p`.`id` AS `product_id`,`p`.`product_name` AS `product_name`,`c`.`id` AS `category_id`,`c`.`name` AS `category_name`,`p`.`store_id` AS `store_id`,coalesce(`p`.`purchase_qty`,0) AS `purchase_qty`,coalesce(`p`.`purchase_amount`,0) AS `purchase_amount`,coalesce(`s`.`sale_qty`,0) AS `sale_qty`,coalesce(`s`.`sale_amount`,0) AS `sale_amount`,(coalesce(`p`.`purchase_qty`,0) - coalesce(`s`.`sale_qty`,0)) AS `balance_qty`,(coalesce(`s`.`sale_amount`,0) - coalesce(`p`.`purchase_amount`,0)) AS `profit_amount` from ((`product_purchase_report` `p` left join `product_sale_report` `s` on((`s`.`id` = `p`.`id`))) left join `product_categories` `c` on((`c`.`id` = `p`.`product_category_id`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
