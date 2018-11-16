<?php

namespace Ueg\Crm\Controller\Adminhtml\crmorderresearch;

use Magento\Backend\App\Action\Context;

class Grid extends \Magento\Backend\App\Action
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
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $block = $this->_view->getLayout()
                ->createBlock('Ueg\Crm\Block\Adminhtml\Crminvoice')
                ->setTemplate('Ueg_Crm::crminvoiceAll MSG Orders/crminvoice.phtml')
                ->toHtml();

        $this->getResponse()->setBody($block);
    }
}
?>