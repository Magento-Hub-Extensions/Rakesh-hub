<?php
namespace DAPL\DigitalSignatr\Block\Adminhtml\Edit\Tab;

class DigitalsignatureTab extends \Magento\Backend\Block\Template
{
	protected $_customerInterface;
	public function __construct(

	\Magento\Backend\Block\Template\Context $context,
	\Magento\Customer\Api\CustomerRepositoryInterface $customerInterface,
	array $data = []

	)

	{
		$this->_customerInterface = $customerInterface;
		parent::__construct($context, $data);
	}


    public function getCurrentCustomerSignature()
    {
    	$customerId = $this->getRequest()->getParam('id');
    	try
    	{
	    	$customer = $this->_customerInterface->getById($customerId);
			$digitalSignatr = $customer->getCustomAttribute('digital_signatr');
			return $digitalSignatr;
    	}

    	catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}


		

    }

}