<?php

namespace Ueg\Crm\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface {

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS ueg_customer_appointment(appointment_id int not null auto_increment, customer_id int,rep_user_id int, rep_user_name varchar(255),rep_user_fullname varchar(255),media varchar(255),contact varchar(255),status varchar(255),subject varchar(255),note text,appointment_time datetime,created_user_id int,created_user_name  varchar(255),created_user_fullname varchar(255),modified_user_id int,modified_user_name varchar(255),modified_user_fullname varchar(255),created_at datetime,update_at datetime, primary key(appointment_id))');
        }

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS ueg_dialer_customer_list(dialer_id int not null auto_increment, user_id int,customer_id int, status smallint,created_at datetime,update_at datetime, primary key(dialer_id))');
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS ueg_asr_customer_list(asr_id int not null auto_increment, user_id int,customer_id int,status int,created_at datetime,update_at datetime, primary key(asr_id))');
        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS `ueg_crm_invoice` (
                  `invoice_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `magento_customer_id` int(11) unsigned NOT NULL DEFAULT 0,
                  `customer_name` varchar(255) DEFAULT NULL,
                  `customer_email` varchar(255) DEFAULT NULL,
                  `magento_order_id` int(11) unsigned NOT NULL DEFAULT 0,
                  `order_number` varchar(255) DEFAULT NULL,
                  `total_qty` decimal(12,4) DEFAULT 0.0000,
                  `subtotal` decimal(12,4) DEFAULT 0.0000,
                  `tax_amount` decimal(12,4) DEFAULT 0.0000,
                  `shipping_amount` decimal(12,4) DEFAULT 0.0000,
                  `discount_amount` decimal(12,4) DEFAULT 0.0000,
                  `grand_total` decimal(12,4) DEFAULT 0.0000,
                  `assigned_user_id` text,
                  `state` varchar(32) DEFAULT NULL,
                  `status` varchar(32) DEFAULT NULL,
                  `created_at` datetime DEFAULT NULL,
                  `qb_invoice_id` varchar(255) DEFAULT NULL,
                  `ebay_id` varchar(255) DEFAULT NULL,
                  `rep` varchar(255) DEFAULT NULL,
                  `tracking_id` varchar(255) DEFAULT NULL,
                  `magento_shipping_id` int(11) unsigned NOT NULL DEFAULT 0,
                  `ship_date` datetime DEFAULT NULL,
                  `shipping_method` varchar(255) DEFAULT NULL,
                  `billing_firstname` varchar(255) DEFAULT NULL,
                  `billing_lastname` varchar(255) DEFAULT NULL,
                  `billing_street` varchar(255) DEFAULT NULL,
                  `billing_city` varchar(255) DEFAULT NULL,
                  `billing_region` varchar(255) DEFAULT NULL,
                  `billing_postcode` varchar(255) DEFAULT NULL,
                  `customer_message` text,
                  `secondary_status` varchar(255) DEFAULT NULL,
                   PRIMARY KEY (`invoice_id`))');
        }


        if (version_compare($context->getVersion(), '1.0.4') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS `ueg_rep_notification` (
              `notification_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `rep_user_id` int(11) unsigned DEFAULT NULL,
              `rep_user_name` varchar(255) DEFAULT NULL,
              `rep_user_fullname` varchar(255) DEFAULT NULL,
              `order_id` int(11) unsigned DEFAULT NULL,
              `order_increment_id` varchar(50) DEFAULT NULL,
              `customer_id` int(11) unsigned DEFAULT NULL,
              `customer_name` varchar(255) DEFAULT NULL,
              `customer_email` varchar(255) DEFAULT NULL,
              `status` smallint(1) NOT NULL DEFAULT 0,
              `flagstatus` smallint(1) NOT NULL DEFAULT 0,
              `message` varchar(255) DEFAULT NULL,
              `note` text,
              `event_created_at` datetime DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `update_at` datetime DEFAULT NULL,
              PRIMARY KEY (`notification_id`))');
        }


        if (version_compare($context->getVersion(), '1.0.5') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS `ueg_individual_coin` (
                        `coin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `customer_id` int(11) unsigned NOT NULL DEFAULT 0,
                        `pcgs` varchar(255) DEFAULT NULL,
                        `year` int(4) DEFAULT NULL,
                        `mint` varchar(255) DEFAULT NULL,
                        `variety` varchar(255) DEFAULT NULL,
                        `desig` varchar(255) DEFAULT NULL,
                        `service` varchar(255) DEFAULT NULL,
                        `min_grade` varchar(255) DEFAULT NULL,
                        `max_grade` varchar(255) DEFAULT NULL,
                        `status` varchar(255) DEFAULT NULL,
                        `note` text,
                        `denom` varchar(255) DEFAULT NULL,
                        `type` varchar(255) DEFAULT NULL,
                        `metal` varchar(255) DEFAULT NULL,
                        `autofil` tinyint(1) DEFAULT NULL,
                        `date_requested` date DEFAULT NULL,
                        `created_at` datetime DEFAULT NULL,
                        `update_at` datetime DEFAULT NULL,
                        `coin_type` smallint(1) NOT NULL DEFAULT 0,
                        `series_id` int(11) unsigned NOT NULL DEFAULT 0,
                        PRIMARY KEY (`coin_id`))');


            $installer->run('CREATE TABLE IF NOT EXISTS `ueg_coin_series` (
                          `series_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `series_name` varchar(255) DEFAULT NULL,
                          `status` smallint(1) NOT NULL DEFAULT 0,
                          `created_at` datetime DEFAULT NULL,
                          `update_at` datetime DEFAULT NULL,
                          PRIMARY KEY (`series_id`))');

            $installer->run('CREATE TABLE IF NOT EXISTS `ueg_coin_series_info` (
                          `series_info_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `series_id` int(11) unsigned NOT NULL DEFAULT 0,
                          `customer_id` int(11) unsigned NOT NULL DEFAULT 0,
                          `service_prefered` varchar(255) DEFAULT NULL,
                          `website` smallint(1) NOT NULL DEFAULT 0,
                          `series_note` text,
                          `registry_name` varchar(255) DEFAULT NULL,
                          PRIMARY KEY (`series_info_id`))');
        }

        if (version_compare($context->getVersion(), '1.0.7') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS `ueg_crm_customer_comment` (
                  `crmcuscomment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `customer_id` int(11) unsigned NOT NULL DEFAULT 0,
                  `user_id` int(11) unsigned NOT NULL DEFAULT 0,
                  `comment` text,
                  `created_at` varchar(255) DEFAULT NULL,
                  PRIMARY KEY (`crmcuscomment_id`))');
        }
        if (version_compare($context->getVersion(), '1.0.8') < 0) {
            $installer->run('CREATE TABLE IF NOT EXISTS `ueg_crm_customer_log` (
                  `crmcuslog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `customer_id` int(11) unsigned NOT NULL DEFAULT 0,
                  `user_id` int(11) unsigned NOT NULL DEFAULT 0,
                  `event_name` text,
                  `created_at` varchar(255) DEFAULT NULL,
                  PRIMARY KEY (`crmcuslog_id`))');
        }
        if (version_compare($context->getVersion(), '1.0.11') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS `ueg_crm_invoice_item` (
                  `invoiceitem_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `parent_id` int(11) unsigned NOT NULL DEFAULT 0,
                  `magento_order_id` varchar(255) DEFAULT NULL,
                  `item_sku` varchar(255) DEFAULT NULL,
                  `item_name` varchar(255) DEFAULT NULL,
                  `qty` int(11) unsigned NOT NULL DEFAULT 0,
                  `unit_price` varchar(255) DEFAULT NULL,
                  `total` varchar(255) DEFAULT NULL,
                  PRIMARY KEY (`invoiceitem_id`))');
        }


        if (version_compare($context->getVersion(), '1.0.12') < 0) {

            $installer->run('CREATE TABLE IF NOT EXISTS `amazonlog` (
                            `amazon_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                            `amazon_order_id` varchar(255) DEFAULT NULL,
                            `date` datetime DEFAULT NULL,
                            `customer_name` varchar(255) DEFAULT NULL,
                            `state` varchar(255) DEFAULT NULL,
                            `product_name` varchar(255) DEFAULT NULL,
                            `qty_sold` decimal(12,4) DEFAULT 0.0000,
                            `price` decimal(12,4) DEFAULT 0.0000,
                            `cost` decimal(12,4) DEFAULT 0.0000,
                            `net_profit_dollar` decimal(12,4) DEFAULT 0.0000,
                            `net_profit_percentage` decimal(12,4) DEFAULT 0.0000,
                            `created_at` datetime DEFAULT NULL,
                            `update_at` datetime DEFAULT NULL,
                            `status` varchar(255) DEFAULT NULL,
                            `customer_name_flag` smallint(6) DEFAULT 0,
                            `sku` varchar(255) DEFAULT NULL,
                            `asn` varchar(255) DEFAULT NULL,
                            `amazon_fee` decimal(12,4) DEFAULT 0.0000,
                            PRIMARY KEY (`amazon_id`))');


            $installer->run('CREATE TABLE IF NOT EXISTS `amazonlog_history` (
                          `amazonhistory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `amazon_id` int(11) unsigned NOT NULL,
                          `cost` decimal(12,4) DEFAULT 0.0000,
                          `modified_user_name` varchar(255) DEFAULT NULL,
                          `modified_at` datetime DEFAULT NULL,
                          PRIMARY KEY (`amazonhistory_id`))');
        }

        if (version_compare($context->getVersion(), '1.0.13') < 0) {
            $installer->run('CREATE TABLE IF NOT EXISTS `saleslog` (
                        `saleslog_id` int(100) unsigned NOT NULL AUTO_INCREMENT,
                        `invoice_id` varchar(40) DEFAULT NULL,
                        `invoice_number` varchar(11) DEFAULT NULL,
                        `order_date` date DEFAULT NULL,
                        `order_number` varchar(25) DEFAULT NULL,
                        `customer_name` text,
                        `billing_name` text,
                        `billing_address` text,
                        `billing_address2` text,
                        `billing_city` varchar(31) DEFAULT NULL,
                        `billing_state` varchar(21) DEFAULT NULL,
                        `billing_zip` varchar(13) DEFAULT NULL,
                        `shipping_address` varchar(41) DEFAULT NULL,
                        `shipping_address2` varchar(41) DEFAULT NULL,
                        `shipping_city` varchar(31) DEFAULT NULL,
                        `shipping_state` varchar(21) DEFAULT NULL,
                        `shipping_zip` varchar(13) DEFAULT NULL,
                        `tax_amount` decimal(10,2) DEFAULT NULL,
                        `order_total` decimal(10,2) DEFAULT NULL,
                        `order_rep` varchar(255) DEFAULT NULL,
                        `qb_shipdate` date DEFAULT NULL,
                        `qb_shipvia` varchar(255) DEFAULT NULL,
                        `qb_message` varchar(255) DEFAULT NULL,
                        `qb_tracking` text,
                        `time_modified` datetime DEFAULT NULL,
                        `itemlistid` varchar(40) DEFAULT NULL,
                        `sortorder` int(11) unsigned DEFAULT NULL,
                        `item_qty` decimal(12,5) DEFAULT NULL,
                        `item_sku` varchar(255) DEFAULT NULL,
                        `item_name` text,
                        `description` varchar(255) NOT NULL,
                        `coin_number` varchar(30) NOT NULL,
                        `unit_price` decimal(13,5) DEFAULT NULL,
                        `product_total` decimal(10,2) DEFAULT NULL,
                        `store_numis` decimal(10,2) DEFAULT NULL,
                        `ebay_bullion` decimal(10,2) DEFAULT NULL,
                        `ebay_numis` decimal(10,2) DEFAULT NULL,
                        `store_bullion` decimal(10,2) DEFAULT NULL,
                        `emails` text,
                        `shipping` decimal(13,5) DEFAULT NULL,
                        `first_name` varchar(255) DEFAULT NULL,
                        `last_name` varchar(255) DEFAULT NULL,
                        `phone` varchar(21) DEFAULT NULL,
                        `lot_number` varchar(30) NOT NULL,
                        `payment_received` varchar(12) NOT NULL,
                        `projected_shipdate` varchar(255) DEFAULT NULL,
                        `donot_edit` int(11) NOT NULL DEFAULT 0,
                        `id` varchar(255) DEFAULT NULL,
                        `account_id` varchar(255) DEFAULT NULL,
                        PRIMARY KEY (`saleslog_id`))');
        }

        if (version_compare($context->getVersion(), '1.0.14') < 0) {
            $installer->run('ALTER TABLE `ueg_crm_customer_log` ADD `updated_data` text NULL;');
        }



        $installer->endSetup();
    }

}
