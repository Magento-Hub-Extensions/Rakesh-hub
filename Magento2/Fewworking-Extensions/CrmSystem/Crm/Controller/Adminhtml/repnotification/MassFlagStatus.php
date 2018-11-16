<?php
namespace Ueg\Crm\Controller\Adminhtml\repnotification;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

/**
 * Class MassDelete
 */
class MassFlagStatus extends \Magento\Backend\App\Action
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
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {

        try {
            $ids = $this->getRequest()->getPost('notification_ids', array());
            foreach ($ids as $id) {
                $model = $this->_repnotification->create();
                $model->setData('flagstatus', $this->getRequest()->getParam('flagstatus'))->setId($id)->save();

                $flag = $this->getRequest()->getParam('flagstatus');

                $model->setFlagstatus($flag)->save();
                //$query = "UPDATE $table SET flagstatus = $flag WHERE notification_id = ". (int)$id;
                //$writeConnection->query($query);
            }
            $this->messageManager->addSuccess(__("Item(s) was successfully updated"));
        }
        catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}