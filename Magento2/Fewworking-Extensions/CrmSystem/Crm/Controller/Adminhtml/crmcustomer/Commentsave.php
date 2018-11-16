<?php

namespace Ueg\Crm\Controller\Adminhtml\crmcustomer;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


class Commentsave extends \Magento\Backend\App\Action
{
     /**
     * @var PageFactory
     */
    protected $resultPagee;
    protected $resultPageFactory;
    protected $authSession;
    protected $crmcuscommentFactory;
    protected $date;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Ueg\Crm\Model\CrmcuscommentFactory $crmcuscommentFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->authSession = $authSession;
        $this->crmcuscommentFactory = $crmcuscommentFactory;
        $this->date = $date;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        // print_r($params); exit;
        if(isset($params['customer_id']) && !empty($params['customer_id'])) {
            if(isset($params['comment']) && !empty($params['comment'])) {
                $user = $this->authSession->getUser();
                $userId = $user->getUserId();
                $data = array(
                    'customer_id' => $params['customer_id'],
                    'user_id' => $userId,
                    'comment' => trim($params['comment']),
                    'created_at' => $this->date->gmtDate(),
                );
                // print_r($data); exit;
                // $comment = Mage::getModel("crm/crmcuscomment");
                $comment = $this->crmcuscommentFactory->create();
                $comment->setData($data)->save();
            }
            $this->_redirect("*/*/view", array('id' => $params['customer_id']));
        }else{
            $this->_redirect("*/*/view");
        }
        
    }
}