<?php


namespace DAPL\DigitalSignatr\Setup;

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

        

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'digital_signatr', [
            'type' => 'text',
            'label' => 'Digital Signature',
            'input' => 'text',
            'source' => '',
            'required' => true,
            'visible' => false,
            'position' => 333,
            'system' => false,
            'backend' => '',
            'backend_type' => 'text'
        ]);

        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'digital_signatr')
            ->addData(['used_in_forms' =>['adminhtml_customer', 'customer_account_create']
        ]);
        $attribute->save();
    }
}
