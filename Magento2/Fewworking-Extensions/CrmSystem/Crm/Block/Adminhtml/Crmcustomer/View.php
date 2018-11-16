<?php

namespace Ueg\Crm\Block\Adminhtml\Crmcustomer;

error_reporting(E_ALL);
ini_set("display_errors", 1);

class View extends \Magento\Backend\Block\Template {

    protected $_customerFactory;
    protected $_addressFactory;
    protected $formKey;
    protected $_registry;
    protected $crmcuscommentFactory;
    protected $crmcuslogFactory;
    protected $authSession;
    protected $userFactory;
    protected $crmadminaclFactory;
    protected $helperBackend;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Customer\Model\AddressFactory $addressFactory, \Magento\Framework\Registry $registry, \Ueg\Crm\Model\CrmcuscommentFactory $crmcuscommentFactory, \Magento\Backend\Model\Auth\Session $authSession, \Magento\User\Model\UserFactory $userFactory, \Ueg\Crm\Model\CrmcuslogFactory $crmcuslogFactory, \Ueg\Crm\Model\CrmadminaclFactory $crmadminacl, \Magento\Backend\Helper\Data $HelperBackend, array $data = []
    ) {

        $this->_customerFactory = $customerFactory;
        $this->_addressFactory = $addressFactory;
        $this->_registry = $registry;
        $this->crmcuscommentFactory = $crmcuscommentFactory;
        $this->authSession = $authSession;
        $this->userFactory = $userFactory;
        $this->crmcuslogFactory = $crmcuslogFactory;

        $this->formKey = $context->getFormKey();

//        added
        $this->crmadminaclFactory = $crmadminacl;
        $this->helperBackend = $HelperBackend;

        parent::__construct($context, $data);
    }

    public function getCustomerById($id) {
        return $this->_customerFactory->create()->load($id);
    }

    public function getCustomerAddressById($addressId) {
        return $this->_addressFactory->create()->load($addressId);
    }

    public function getFormKey() {

        return $this->formKey->getFormKey();
    }

    public function getRegistry() {
        return $this->_registry;
    }

    public function getCrmcuscommentCollection($customerId) {
        $collection = $this->crmcuscommentFactory->create()->getCollection()
                ->addFieldToFilter('customer_id', $customerId);
        $collection->getSelect()->order("created_at desc");

        return $collection;
    }

    public function getAdminUser() {
        return $this->userFactory->create();
    }

    public function getCrmcuslogCollection($customerId) {
        $collection = $this->crmcuslogFactory->create()
                ->getCollection()
                ->addFieldToFilter('customer_id', $customerId);
        $collection->getSelect()->order("created_at desc");

        return $collection;
    }

//    added

    public function getAuthSession() {
        return $this->authSession;
    }

    public function getCrmadminaclModel() {
        return $this->crmadminaclFactory->create();
    }
    public function goHome(){ 
        return $this->helperBackend->getHomePageUrl();
    }

}
