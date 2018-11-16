<?php

namespace Ueg\Crm\Controller\Adminhtml\dialer;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Dashclaim extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPagee;

    protected $_dialerFactory;

    protected $authSession;

    protected $_customerObject;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Ueg\Crm\Model\DialerFactory $DialerFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Customer\Model\CustomerFactory $customerObject,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_dialerFactory = $DialerFactory;
        $this->authSession = $authSession;
        $this->_customerObject = $customerObject;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
       $this->processLead();
       $this->messageManager->addSuccess(__('Claim is updated'));
       $this->_redirect('crm/dialer/dashboard');
    }


    protected function processLead()
    {
        // set all to 1
        $todayDate = date('Y-m-d H:i:s');
        $userId = $this->authSession->getUser()->getUserId();
        $dialerCollection =$this->_dialerFactory->create()->getCollection()
                                ->addFieldToFilter('user_id', $userId)
                                ->addFieldToFilter('status', 0);
        if(count($dialerCollection) > 0) {
            foreach ($dialerCollection as $_dialer) {
                $dialer = $this->_dialerFactory->create()->load($_dialer->getId());
                $dialer->setData('status', 1);
                $dialer->setData('update_at', $todayDate);
                $dialer->save();
            }
        }

        $customerCollection = $this->_customerObject->create()->getCollection()
                                  ->addAttributeToFilter('dialer_pool', '1');
        $customerCollection->getSelect()->orderRand();
        $customerCollection->getSelect()->limit(5);

        if(count($customerCollection) > 0) {
            foreach ( $customerCollection as $_customer ) {
                $dialerObj = $this->_dialerFactory->create();
                $data = array(
                    'user_id' => $userId,
                    'customer_id' => $_customer->getId(),
                    'status' => 0,
                    'created_at' => $todayDate,
                    'update_at' => $todayDate
                );
                $dialerObj->setData($data)->save();
            }
        }
    }
}
?>