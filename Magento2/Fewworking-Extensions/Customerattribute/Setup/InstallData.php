<?php


namespace DAPL\Customerattribute\Setup;

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

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'seller_register_plan', [
            'type' => 'varchar',
            'label' => 'Seller Register Plan',
            'input' => 'select',
            'source' => 'DAPL\Customerattribute\Model\Customer\Attribute\Source\SellerRegisterPlan',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => ''
        ]);

        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'seller_register_plan')
            ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]]);
        $attribute->save();
        

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'seller_product_number', [
            'type' => 'varchar',
            'label' => 'Seller Register product Number',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => ''
        ]);

        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'seller_product_number')
            ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]]);
        $attribute->save();
    }
}
