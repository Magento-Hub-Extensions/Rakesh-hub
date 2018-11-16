<?php

namespace Ueg\Crm\Controller\Adminhtml\dialer;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Dashboard extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Ueg_Crm::dialer';
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
        $resultPage->setActiveMenu('Ueg_Crm::dialer');
        $resultPage->addBreadcrumb(__('Dialer Dashboard'), __('Dialer Dashboard'));
        $resultPage->addBreadcrumb(__('Manage item'), __('Dialer Dashboard'));
        $resultPage->getConfig()->getTitle()->prepend(__('Dialer Dashboard'));

        return $resultPage;
    }
}
?>