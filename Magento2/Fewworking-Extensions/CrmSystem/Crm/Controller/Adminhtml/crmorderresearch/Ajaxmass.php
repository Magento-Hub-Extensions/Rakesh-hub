<?php

namespace Ueg\Crm\Controller\Adminhtml\crmorderresearch;

use Magento\Backend\App\Action\Context;

class Ajaxmass extends \Magento\Backend\App\Action
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
        $idVar = $params['id_var'];
        parse_str( $idVar, $idParams );
        $massVar = $params['mass_var'];
        parse_str( $massVar, $massParams );
        if(isset($idParams['update_invoice'])) {
            

            if(isset($massParams['mass_action_invoice']) && !empty($massParams['mass_action_invoice'])) {
                $optionName = $massParams['mass_action_invoice'];
                if(isset($massParams['mass_action_option_invoice']) && isset($massParams['mass_action_option_invoice'][$optionName])) {
                    $optionValue = $massParams['mass_action_option_invoice'][$optionName];
                    $customerIds = array();
                    foreach ($idParams['update_invoice'] as $id => $value) {
                        if($value == 1) {
                            $invoice = $this->_crmInvoice->create()->load($id);
                            $customerId = $invoice->getData('magento_customer_id');
                            if(isset($customerId) && !empty($customerId) && $customerId != 0) {
                                $customerIds[] = $customerId;
                            }
                        }
                    }
                    $customerIds = array_unique($customerIds);
                    if(isset($customerIds) && !empty($customerIds)) {
                        foreach ($customerIds as $customer_id) {
                            $customer = $this->_customerObject->create()->load($customer_id);
                            $customer->setData($optionName, $optionValue);
                            $customer->save();
                        }
                    }
                }
            }
        }
        echo 1;
    }
}
?>