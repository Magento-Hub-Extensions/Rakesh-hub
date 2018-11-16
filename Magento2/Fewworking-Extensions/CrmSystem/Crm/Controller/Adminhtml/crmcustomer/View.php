<?php

namespace Ueg\Crm\Controller\Adminhtml\crmcustomer;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Backend\App\Action
{
     /**
     * @var PageFactory
     */
    protected $resultPagee;
    protected $resultPageFactory;

    protected $_registry;
     protected $authSession;
     protected $date;
     protected $crmcuslog;
     protected $coreSession;
     protected $customerFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Ueg\Crm\Model\CrmcuslogFactory $crmcuslogFactory,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_registry = $registry;
        $this->authSession = $authSession;
        $this->date = $date;
        $this->crmcuslogFactory = $crmcuslogFactory;
        $this->coreSession = $coreSession;
        $this->customerFactory = $customerFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        // print_r($this->getRequest()->getParams()); exit;
        $this->_initCustomer();
        $resultPage = $this->resultPageFactory->create();

        $customer = $this->_registry->registry('current_customer');

        $log = $this->coreSession->getLog();
        if($customer->getId() && (!isset($log) || $log != 1)) 
        {
            $user =$this->authSession;
            $userId = $user->getUser()->getUserId();
            $data = array(
                'customer_id' => $customer->getId(),
                'user_id' => $userId,
                'event_name' => "Account Accessed",
                'created_at' => $this->date->gmtDate(),
            );
               
            $comment = $this->crmcuslogFactory->create();
            try {
                $savedid = $comment->setData($data)->save()->getId(); 
            } catch (\Exception $e) {
                echo $e->getMessage(); exit;
            }

            $this->coreSession->setLog(1);
        }


        //$resultPage->setActiveMenu('Ueg_Crm::crm');
        return $resultPage;
    }

    protected function _initCustomer($idFieldName = 'id')
    {
        // $this->_title($this->__('Customers'))->_title($this->__('Manage Customers'));
       
        $customerId = (int) $this->getRequest()->getParam($idFieldName);
        $customer=false;
        if ($customerId) {           
            $customer = $this->customerFactory->create()->load($customerId);
            
        } else {
            $this->_redirect("adminhtml/dashboard/");
        }
        
        $this->_registry->register('current_customer', $customer);
        return $this;
    }


}