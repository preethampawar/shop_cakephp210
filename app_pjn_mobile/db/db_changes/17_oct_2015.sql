-- create breakages table
CREATE TABLE `breakages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(55) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_category_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_units` int(10) unsigned DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `breakage_date` date DEFAULT NULL,
  `product_name` varchar(55) DEFAULT NULL,
  `category_name` varchar(55) DEFAULT NULL,
  `store_name` varchar(55) DEFAULT NULL,
  `created` datetime,
  `modified` datetime,
  PRIMARY KEY (`id`),
  KEY `productIDSalesIndex` (`product_id`),
  KEY `storeIdSalesIndex` (`store_id`)
) ENGINE=MyISAM;

-- create view to get breakage report
CREATE VIEW `product_breakage_report` AS 
SELECT
  `pr`.`id`                  AS `id`,
  `pr`.`name`                AS `product_name`,
  `pr`.`product_category_id` AS `product_category_id`,
  `pr`.`store_id`            AS `store_id`,
  COALESCE(SUM(`b`.`total_units`),0) AS `breakage_qty`,
  COALESCE(SUM(`b`.`total_amount`),0) AS `breakage_amount`
FROM (`products` `pr`
   LEFT JOIN `breakages` `b`
     ON ((`pr`.`id` = `b`.`product_id`)))
GROUP BY `pr`.`id`

-- alter view to get stock report
DELIMITER $$

ALTER VIEW `product_stock_report` AS 
SELECT
  `p`.`id`           AS `product_id`,
  `p`.`product_name` AS `product_name`,
  `c`.`id`           AS `category_id`,
  `c`.`name`         AS `category_name`,
  `p`.`store_id`     AS `store_id`,
  COALESCE(`p`.`purchase_qty`,0) AS `purchase_qty`,
  COALESCE(`p`.`purchase_amount`,0) AS `purchase_amount`,
  COALESCE(`s`.`sale_qty`,0) AS `sale_qty`,
  COALESCE(`s`.`sale_amount`,0) AS `sale_amount`,
  COALESCE(`b`.`breakage_qty`,0) AS `breakage_qty`,
  COALESCE(`b`.`breakage_amount`,0) AS `breakage_amount`,
  (COALESCE(`p`.`purchase_qty`,0) - COALESCE(`s`.`sale_qty`,0) - COALESCE(`b`.`breakage_qty`,0)) AS `balance_qty`,
  (COALESCE(`s`.`sale_amount`,0) - COALESCE(`p`.`purchase_amount`,0)) AS `profit_amount`
FROM ((`product_purchase_report` `p`
    LEFT JOIN `product_sale_report` `s`
      ON ((`s`.`id` = `p`.`id`)))
    LEFT JOIN `product_breakage_report` `b`
      ON ((`b`.`id` = `p`.`id`))  
   LEFT JOIN `product_categories` `c`
     ON ((`c`.`id` = `p`.`product_category_id`)))$$

DELIMITER ;
