<?php

namespace Ueg\Amazon\Controller\Adminhtml\amazon;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPagee;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {

        //echo 'asdas';exit();
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ueg_Amazon::amazon');
        $resultPage->addBreadcrumb(__('Manager New Amazon Items'), __('Manager New Amazon Items'));
        $resultPage->addBreadcrumb(__('Manage item'), __('Manager New Amazon Items'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manager New Amazon Items'));

        return $resultPage;
    }
}
?>