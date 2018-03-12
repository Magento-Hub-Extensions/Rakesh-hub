<?php
namespace DAPL\Paypalcustompayment\Controller\Seller;
use DAPL\Paypalcustompayment\Helper\Data;
use Lof\MarketPlace\Model\Seller;
use Magento\Framework\Message\ManagerInterface;

 class CancelUrl extends \Magento\Framework\App\Action\Action
    {


    	protected $customerSession;

    	protected $paypal;
    	protected $seller;
    	protected $message;



		public function __construct(
			\Magento\Framework\App\Action\Context $context,
			\Magento\Customer\Model\Session $customerSession,
			Data $data,
			Seller $seller,
			ManagerInterface $message
		)

		{
			$this->customerSession = $customerSession;
			$this->paypal = $data;
			$this->seller = $seller;
			$this->message = $message;
			parent::__construct($context);
		}




        
        public function execute() {
            

            $token = $this->getRequest()->getParam('token');

			$CustomerMail = $this->customerSession->getCustomer()->getEmail();
			$Currentseller = $this->seller->load($CustomerMail,'email');

            if($token)
            {
            		$Currentseller->setStatus(0)->save();
            		$errmessage = 'Payment is successfully canceled and retry again!!';
				    $this->message->addError($errmessage);//For Error Message
            		$this->_redirect ('lofmarketplace/seller/login');
            }

            
        }

  
    }