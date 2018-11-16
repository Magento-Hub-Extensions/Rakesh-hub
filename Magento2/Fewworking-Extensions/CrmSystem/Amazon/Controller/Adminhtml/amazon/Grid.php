<?php

namespace Ueg\Amazon\Controller\Adminhtml\amazon;

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
                ->createBlock('Ueg\Amazon\Block\Adminhtml\Amazon')
                ->setTemplate('Ueg_Amazon::amazon/amazon.phtml')
                ->toHtml();

        $this->getResponse()->setBody($block);
    }
}
?>