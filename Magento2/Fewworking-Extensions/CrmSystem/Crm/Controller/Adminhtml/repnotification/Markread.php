<?php

namespace Ueg\Crm\Controller\Adminhtml\repnotification;

use Magento\Backend\App\Action\Context;

class Markread extends \Magento\Backend\App\Action
{


    protected $_repnotification;


    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Ueg\Crm\Model\RepnotificationFactory $repFactory
    ) {
        $this->_repnotification = $repFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
            if( $this->getRequest()->getParam("id") > 0 ) {
                try {
                    $model = $this->_repnotification->create();
                    $model->setData('status', 1)->setId($this->getRequest()->getParam("id"))->save();
                    $this->messageManager->addSuccess(__("Item was successfully marked as read"));
                    $this->_redirect("*/*/");
                }
                catch (Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                    $this->_redirect("*/*/");
                }
            }
            $this->_redirect("*/*/");
    }
}
?>