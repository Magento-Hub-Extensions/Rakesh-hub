<?php

namespace Ueg\Crm\Controller\Adminhtml\appointment;

class CustomerGrid extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute() {
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout->getLayout()->getBlock('admin.crm.appointment.grid');
        return $resultLayout;
    }

}
