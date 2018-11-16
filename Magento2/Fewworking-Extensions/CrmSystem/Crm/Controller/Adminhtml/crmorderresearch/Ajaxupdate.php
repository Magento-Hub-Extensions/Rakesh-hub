<?php

namespace Ueg\Crm\Controller\Adminhtml\crmorderresearch;

use Magento\Backend\App\Action\Context;

class Ajaxupdate extends \Magento\Backend\App\Action
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
        $params = $this->getRequest()->getParams();
        $formVar = $params['form_var'];
        parse_str( $formVar, $formParams );
        if(isset($formParams)) {
            if(isset($formParams['update_invoice']) && count($formParams['update_invoice'])) {
                $idsArray = array_keys($formParams['update_invoice']);
                foreach ($idsArray as $id) {
                    $secondaryStatus = "";
                    if(isset($formParams['secondary_status'][$id]) && count($formParams['secondary_status'][$id])) {
                        $secondaryStatus = $formParams['secondary_status'][$id];
                    }
                    $invoice = $this->_crmInvoice->create()->load($id);
                    $invoice->setData('secondary_status', $secondaryStatus);
                    $invoice->setId($id)->save();
                }
            }
        }
        echo 1;
    }
}
?>