<?php

namespace Ueg\Crm\Controller\Adminhtml\repnotification;

use Magento\Backend\App\Action\Context;

class Markflag extends \Magento\Backend\App\Action
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
            //$resource = Mage::getSingleton('core/resource');
            //$writeConnection = $resource->getConnection('core_write');
            //$table = $resource->getTableName('ueg_rep_notification');
        if( $this->getRequest()->getParam("id") > 0 ) {
            try {
                $model = $this->_repnotification->create()->load($this->getRequest()->getParam("id"));
                $model->setData('flagstatus', 1)->setId($this->getRequest()->getParam("id"))->save();

                //$query = "UPDATE $table SET flagstatus = 1 WHERE notification_id = ". (int)$this->getRequest()->getParam("id");

                $model->setFlagstatus(1)->save();
                //$writeConnection->query($query);

                $this->messageManager->addSuccess(__("Item was successfully flagged"));
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