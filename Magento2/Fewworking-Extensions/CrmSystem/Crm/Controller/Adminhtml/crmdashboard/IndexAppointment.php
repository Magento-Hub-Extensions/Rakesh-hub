<?php

namespace Ueg\Crm\Controller\Adminhtml\crmdashboard;

use Magento\Backend\App\Action\Context;

class IndexAppointment extends \Magento\Backend\App\Action
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
                ->createBlock('Ueg\Crm\Block\Adminhtml\Appointment')
                ->setTemplate('Ueg_Crm::appointment/appointment.phtml')
                ->toHtml();

        $this->getResponse()->setBody($block);
    }
}
?>