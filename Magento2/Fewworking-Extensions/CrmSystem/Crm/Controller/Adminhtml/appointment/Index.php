<?php

namespace Ueg\Crm\Controller\Adminhtml\appointment;

error_reporting(E_ALL);
ini_set("display_errors", 1);

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
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ueg_Crm::appointment');
        $resultPage->addBreadcrumb(__('Ueg'), __('Ueg'));
        $resultPage->addBreadcrumb(__('Manage item'), __('Calender'));
        $resultPage->getConfig()->getTitle()->prepend(__('Calender'));

        return $resultPage;
    }
}
?>