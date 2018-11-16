<?php

namespace Ueg\Crm\Block\Adminhtml;

error_reporting(E_ALL);
ini_set("display_errors", 1);

class Crmcustomer extends \Magento\Backend\Block\Template {
    protected $_customerFactory;
 
    protected $_addressFactory;

    protected $formKey;

    public function __construct(
             \Magento\Backend\Block\Template\Context $context,
             \Magento\Customer\Model\CustomerFactory $customerFactory,
            \Magento\Customer\Model\AddressFactory $addressFactory,
            array $data = []
    ) {
        $this->formKey = $context->getFormKey();
        $this->_customerFactory = $customerFactory;
        $this->_addressFactory = $addressFactory;
        parent::__construct($context, $data);
    }

    public function getCustomerById($id) {
        return $this->_customerFactory->create()->load($id);
    }
 
    public function getCustomerAddressById($addressId) {
        return $this->_addressFactory->create()->load($addressId);
    }

    public function getFormKey(){

     return $this->formKey;
     
    }

}
