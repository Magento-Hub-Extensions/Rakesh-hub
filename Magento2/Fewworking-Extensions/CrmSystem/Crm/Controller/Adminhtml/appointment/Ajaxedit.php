<?php

namespace Ueg\Crm\Controller\Adminhtml\appointment;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Ajaxedit extends \Magento\Backend\App\Action {

    /**
     * @var PageFactory
     */
    protected $resultPagee;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
    Context $context, PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute() {

        $resultPage = $this->resultPageFactory->create();
        $this->getResponse()->setBody(
                $resultPage->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Customer\Edit\Tab\Appointment\View')
                        ->setAppointmentId($this->getRequest()->getParam('id'))
                        ->setTemplate('crm/customer/edit/tab/appointment/edit.phtml')
                        ->toHtml()
        );
    }

}

?>