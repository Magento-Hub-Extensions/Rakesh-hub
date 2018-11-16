<?php

namespace Ueg\Amazon\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0){

				$installer->run('CREATE TABLE IF NOT EXISTS `amazon` (
				  `amazon_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `title` varchar(255) NOT NULL,
				  `filename` varchar(255) NOT NULL,
				  `content` text NOT NULL,
				  `status` smallint(6) NOT NULL DEFAULT 0,
				  `created_time` datetime DEFAULT NULL,
				  `update_time` datetime DEFAULT NULL,
				  PRIMARY KEY (`amazon_id`))');
		}

        $installer->endSetup();

    }
}