<?php
namespace Ueg\Crm\Controller\Adminhtml\repnotification;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class MassStatus extends \Magento\Backend\App\Action
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
     * Update blog post(s) status action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
      try {
            $ids = $this->getRequest()->getPost('notification_ids', array());
            foreach ($ids as $id) {
                $model = $this->_repnotification->create();
                $model->setData('status', $this->getRequest()->getParam('status'))->setId($id)->save();
            }
            $this->messageManager->addSuccess(__("Item(s) was successfully updated"));
        }
        catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

}