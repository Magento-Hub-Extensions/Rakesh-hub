<?php

namespace Ueg\Crm\Controller\Adminhtml\overdue;

use Magento\Backend\App\Action\Context;

class Grid extends \Magento\Backend\App\Action
{


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
                ->createBlock('Ueg\Crm\Block\Adminhtml\Overdue')
                ->setTemplate('Ueg_Crm::overdue/overdue.phtml')
                ->toHtml();

        $this->getResponse()->setBody($block);
    }
}
?>