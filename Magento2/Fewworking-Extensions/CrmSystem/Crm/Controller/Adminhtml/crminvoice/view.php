<?php

namespace Ueg\Crm\Controller\Adminhtml\crminvoice;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class view extends \Magento\Backend\App\Action
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
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ueg_Crm::crm');
        $resultPage->addBreadcrumb(__('All MSG Orders'), __('View MSG Orders'));
        $resultPage->addBreadcrumb(__('Manage item'), __('View MSG Orders'));

        return $resultPage;
    }
}
?>