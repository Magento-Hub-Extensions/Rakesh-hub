<?php


namespace Ueg\Crm\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
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
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        
        if (version_compare($context->getVersion(), '1.0.2') < 0){

                    $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'asr_pool', [
                    'type' => 'varchar',
                    'label' => 'ASR pool',
                    'input' => 'select',
                    'source' => 'Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerAsrOptions',
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



                    $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'asr_pool')
                    ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                    ]]);
                    $attribute->save();


         }



         if (version_compare($context->getVersion(), '1.0.3') < 0){

            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'assigned_to', [
                'type' => 'text',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'label' => 'Assigned to',
                'input' => 'multiselect',
                'source' => 'Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerAssignedToOptions',
                'required' => false,
                'visible' => true,
                'position' => 333,
                'system' => false
            ]);


            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'assigned_to')
                ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]]);
            $attribute->save();
         }


         if (version_compare($context->getVersion(), '1.0.3') < 0){

            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'admin_only', [
                'type' => 'int',
                'backend' => '',
                'label' => 'Admin Only',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'visible' => true,
                'position' => 333,
                'system' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'is_searchable_in_grid' => true
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'admin_only')
                ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]]);
            $attribute->save();
         }

         if (version_compare($context->getVersion(), '1.0.4') < 0){

            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'hot_client', [
                'type' => 'int',
                'backend' => '',
                'label' => 'Hot Client',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'visible' => true,
                'position' => 333,
                'system' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'is_searchable_in_grid' => true
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'hot_client')
                ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]]);
            $attribute->save();
         }


         if (version_compare($context->getVersion(), '1.0.5') < 0){

            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'client_code', [
                'type' => 'varchar',
                'backend' => '',
                'label' => 'Client Code',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 333,
                'system' => false
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'client_code')
                ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]]);
            $attribute->save();
         }

         if (version_compare($context->getVersion(), '1.0.5') < 0){

            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'last_contacted', [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => 'Magento\Eav\Model\Entity\Attribute\Frontend\Datetime',
                'label' => 'Last Contacted',
                'input' => 'date',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 333,
                'system' => false
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'last_contacted')
                ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]]);
            $attribute->save();
         }


         // added customer attributes

        if (version_compare($context->getVersion(), '1.0.6') < 0 ) {
           
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'last_contacted', [
                'type' => 'datetime',
                'label' => 'Last Contacted',
                'input' => 'date',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 1001,
                'system' => false,
                'backend' => ''
            ]);
             $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'last_contacted')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();




            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'ebay_id', [
                'type' => 'varchar',
                'label' => 'eBay ID',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 1002,
                'system' => false,
                'backend' => ''
            ]);
             $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'ebay_id')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();


            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'last_communication', [
                'type' => 'datetime',
                'label' => 'Last Communication',
                'input' => 'date',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 1003,
                'system' => false,
                'backend' => ''
            ]);
             $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'last_communication')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();


            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'preferred_contact_time', [
                'type' => 'varchar',
                'label' => 'Preferred Contact Time',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 1004,
                'system' => false,
                'backend' => ''
            ]);
             $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'preferred_contact_time')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();
     
             ///////////// preferred_contact_method
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'preferred_contact_method', [
                'type' => 'int',
                'label' => 'Preferred Contact Method',
                'input' => 'select',
                'source' => 'Ueg\Crm\Model\Customer\Attribute\Source\Customeroptions15242422063',
                'required' => false,
                'visible' => true,
                'position' => 1005,
                'system' => false,
                'backend' => ''
            ]);
            
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'preferred_contact_method')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();


            ///////////// source_sale
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'source_sale', [
                'type' => 'int',
                'label' => 'Source of Sale',
                'input' => 'select',
                'source' => 'Ueg\Crm\Model\Customer\Attribute\Source\Customeroptions15242422064',
                'required' => false,
                'visible' => true,
                'position' => 1006,
                'system' => false,
                'backend' => ''
            ]);
            
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'source_sale')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();


            ///////////// preferred_payment
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'preferred_payment', [
                'type' => 'int',
                'label' => 'Preferred Payment',
                'input' => 'select',
                'source' => 'Ueg\Crm\Model\Customer\Attribute\Source\Customeroptions15242422065',
                'required' => false,
                'visible' => true,
                'position' => 1007,
                'system' => false,
                'backend' => ''
            ]);
            
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'preferred_payment')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();



            ///////////// phone_type
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'phone_type', [
                'type' => 'int',
                'label' => 'Phone Type',
                'input' => 'select',
                'source' => 'Ueg\Crm\Model\Customer\Attribute\Source\Customeroptions15242422066',
                'required' => false,
                'visible' => true,
                'position' => 1008,
                'system' => false,
                'backend' => ''
            ]);
            
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'phone_type')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();


            ///////////// customer_notes
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'customer_notes', [
                'type' => 'text',
                'label' => 'Notes',
                'input' => 'textarea',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 1009,
                'system' => false,
                'backend' => ''
            ]);
             $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'customer_notes')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();

        } //if ends

        // added customer attributes

        if (version_compare($context->getVersion(), '1.0.10') < 0 ) {
           
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'phone', [
                'type' => 'varchar',
                'label' => 'Phone',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 1010,
                'system' => false,
                'backend' => ''
            ]);
             $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'phone')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();

            //Additional Phone
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'additional_phone', [
                'type' => 'text',
                'label' => 'Additional Phone',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 1011,
                'system' => false,
                'backend' => ''
            ]);
             $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'additional_phone')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();

        
            ///////////// additional phone_type
            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'additional_phone_type', [
                'type' => 'int',
                'label' => 'Additional Phone Type',
                'input' => 'select',
                'source' => 'Ueg\Crm\Model\Customer\Attribute\Source\Customeroptions15242422067',
                'required' => false,
                'visible' => true,
                'position' => 1012,
                'system' => false,
                'backend' => ''
            ]);
            
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'additional_phone_type')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();

            $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'customer_company', [
                'type' => 'text',
                'label' => 'Company',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 1010,
                'system' => false,
                'backend' => ''
            ]);
             $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'customer_company')
            ->addData(['used_in_forms' => [
                    'adminhtml_customer'
                ]
            ]);
            $attribute->save();

        } //if ends

    }
}
