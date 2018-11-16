<?php
namespace Ueg\Crm\Controller\Adminhtml\customer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Setdialerpool extends \Magento\Backend\App\Action
{

    protected $customerObject;

     public function __construct(
         Context $context,
         \Magento\Customer\Model\CustomerFactory $customerObject
    ) 
    {
        $this->customerObject = $customerObject;
        parent::__construct($context);
    }

    /**
     * Update blog post(s) status action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $paramValues = $this->getRequest()->getParams();

        $customerIds = $paramValues['selected'];
        $hotClient = $paramValues['dialer_pool'];

        foreach($customerIds as $customerId)
        {
            $customerModel = $this->customerObject->create()->load($customerId);
            $customerModel->getResource()->load($customerModel, $customerId);
            $customerModel->setData('dialer_pool', $hotClient)
                      ->setAttributeSetId(\Magento\Customer\Api\CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER);
            $customerModel->getResource()->save($customerModel);
        }

        $this->messageManager->addSuccess(__('Total of %1 record(s) have been updated.', count($customerIds)));
        $this->_redirect('customer/index/index');
        
    }

}