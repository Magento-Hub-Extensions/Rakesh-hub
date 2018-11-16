<?php

namespace Ueg\Crm\Setup;

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

		$installer->run('CREATE TABLE IF NOT EXISTS crmadminacl (
      `crmacl_id` int(11) unsigned NOT NULL auto_increment,
      `role_id` int(11) unsigned NOT NULL default 0,
      `account_overview_read` text default NULL,
      `account_overview_write` text default NULL,
      `inventory_read` text default NULL,
      `account_view` smallint(6) NOT NULL default 0,
      `account_logview` smallint(6) NOT NULL default 0,
      `order_street_view` smallint(6) NOT NULL default 0,
      `sales_log_delete` smallint(6) NOT NULL default 0,
      PRIMARY KEY (`crmacl_id`)
)');


		

		}

        $installer->endSetup();

    }
}