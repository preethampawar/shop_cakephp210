-- 8-12-2019. Add new column "mrp_rounding_off"
ALTER TABLE `invoices` ADD `mrp_rounding_off` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `credit_balance`;