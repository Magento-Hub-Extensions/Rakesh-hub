<?php

namespace Ueg\Crm\Controller\Adminhtml\crmorderresearch;

use Magento\Backend\App\Action\Context;

class Ajaxlistall extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPagee;

    protected $_crmInvoice;

    protected $_customerObject;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Ueg\Crm\Model\CrminvoiceFactory $crmInvoice,
        \Magento\Customer\Model\CustomerFactory $customerObject
    ) {
        $this->_crmInvoice = $crmInvoice;
        $this->_customerObject = $customerObject;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $response = array();

        
        $response['list'] = $this->_view->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Crmorderresearch')
                ->setTemplate('Ueg_Crm::crmorderresearch/index/list.phtml')
                ->toHtml();
        $response['pagination'] = $this->_view->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Crmorderresearch')
                ->setTemplate('Ueg_Crm::crmorderresearch/index/pagination.phtml')
                ->toHtml();

        echo json_encode($response);
    }
}
?>