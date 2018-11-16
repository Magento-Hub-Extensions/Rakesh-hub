<?php
/*
 * 
 */
namespace Ueg\Crm\Controller\Adminhtml\crminvoice;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Magento\Backend\App\Action\Context;

class Ajaxlist extends \Magento\Backend\App\Action {

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
    Context $context, \Ueg\Crm\Model\CrminvoiceFactory $crmInvoice, \Magento\Customer\Model\CustomerFactory $customerObject
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
    public function execute() {
        $response = array();
        $response['list'] = $this->_view->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Crmcustomer\View\Invoice')->setTemplate('crm/crmcustomer/view/invoice/list.phtml')->toHtml();
        $response['pagination'] = $this->_view->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Crmcustomer\View\Invoice')->setTemplate('crm/crmcustomer/view/invoice/pagination.phtml')->toHtml();

        echo json_encode($response);
    }

}

?>