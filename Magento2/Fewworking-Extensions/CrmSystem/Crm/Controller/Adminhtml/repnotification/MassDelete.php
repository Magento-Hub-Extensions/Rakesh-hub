<?php
namespace Ueg\Crm\Controller\Adminhtml\repnotification;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
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
        $itemIds = $this->getRequest()->getParam('crmdashboard');
        if (!is_array($itemIds) || empty($itemIds)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($itemIds as $itemId) {
                    $post = $this->_objectManager->get('Ueg\Crm\Model\Dialer')->load($itemId);
                    $post->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($itemIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath('crm/*/index');
    }
}