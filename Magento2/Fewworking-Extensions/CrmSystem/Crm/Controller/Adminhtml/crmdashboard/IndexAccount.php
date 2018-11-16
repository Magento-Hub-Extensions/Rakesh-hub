<?php

namespace Ueg\Crm\Controller\Adminhtml\crmdashboard;

use Magento\Backend\App\Action\Context;

class IndexAccount extends \Magento\Backend\App\Action
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
                ->createBlock('Ueg\Crm\Block\Adminhtml\Crmdashboard\Account')
                ->setTemplate('Ueg_Crm::crmdashboard/account.phtml')
                ->toHtml();

        $this->getResponse()->setBody($block);
    }
}
?>