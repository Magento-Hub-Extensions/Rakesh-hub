<?php

namespace Ueg\Crm\Controller\Adminhtml\appointment;

use Magento\Backend\App\Action\Context;

class Gridajax extends \Magento\Backend\App\Action {

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
    public function execute() {

        $this->getResponse()->setBody(
                $this->_view->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Customer\Edit\Tab\Appointment\Grid')->toHtml()
        );
    }

}

?>