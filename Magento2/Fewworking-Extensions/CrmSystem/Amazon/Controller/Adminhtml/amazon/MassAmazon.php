<?php
namespace Ueg\Amazon\Controller\Adminhtml\amazon;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
error_reporting(E_ALL);
ini_set('display_errors', 1);

class MassAmazon extends \Magento\Backend\App\Action
{

    protected $action;

    /**
     * @param Action\Context $context
     */
    public function __construct(
    Action\Context $context,
    \Magento\Catalog\Model\ResourceModel\Product\Action $action


    )
    {
        $this->action = $action;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        //echo '<pre>';print_r($this->getRequest()->getParams());exit();
        $productIds = (array)$this->getRequest()->getParam('product');
        $storeId    = (int)$this->getRequest()->getParam('store', 0);

        $sell_amazon =  0;
        //$fee = 6;
        try {
            $this->action->updateAttributes($productIds, array('sell_amazon' => $sell_amazon), $storeId);

            $this->messageManager->addSuccess(__('Total of %1 record(s) have been updated.', count($productIds))
            );
        }
        catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        catch (\Exception $e) {
            $this->messageManager->addException($e,__('An error occurred while updating the product(s) status.'));
        }
        $this->_redirect('*/*/index');
    }
}