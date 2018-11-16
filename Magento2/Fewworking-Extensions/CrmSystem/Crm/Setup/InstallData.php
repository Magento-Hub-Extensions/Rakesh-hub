<?php


namespace Ueg\Crm\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'dialer_pool', [
            'type' => 'varchar',
            'label' => 'Dialer pool',
            'input' => 'select',
            'source' => 'Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerDialerOptions',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => '',
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'is_searchable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'dialer_pool')
            ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]]);
        $attribute->save();
    }
}
