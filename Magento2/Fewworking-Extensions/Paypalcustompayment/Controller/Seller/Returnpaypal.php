<?php
namespace DAPL\Paypalcustompayment\Controller\Seller;


use DAPL\Paypalcustompayment\Helper\Data;
use Lof\MarketPlace\Model\Seller;
use Magento\Framework\Message\ManagerInterface;

 class Returnpaypal extends \Magento\Framework\App\Action\Action
    {


    	protected $customerSession;

    	protected $paypal;
    	protected $seller;
    	protected $message;

    	protected $planchoose;



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
            
                    $CustomerMail = $this->customerSession->getCustomer()->getEmail();

                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $paypalSession = $objectManager->create('Magento\Customer\Model\Session');
                    $products = $paypalSession->getProductDetails();
                    $charges = $paypalSession->getProductCharges();



                    $token = $this->getRequest()->getParam('token');
                    $PayerID =  $this->getRequest()->getParam('PayerID');


					$padata  = 	'&TOKEN='.urlencode($token);
					$padata .= 	'&PAYERID='.urlencode($PayerID);
					$padata .= 	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");


				foreach($products as $p => $item){
					
					$padata .=	'&L_PAYMENTREQUEST_0_NAME'.$p.'='.urlencode($item['ItemName']);
					$padata .=	'&L_PAYMENTREQUEST_0_AMT'.$p.'='.urlencode($item['ItemPrice']);
					$padata .=	'&L_PAYMENTREQUEST_0_QTY'.$p.'='. urlencode($item['ItemQty']);

					$this->planchoose = $item['ItemName'];
				}



				$padata .= 	'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($this->paypal->GetProductsTotalAmount($products));
				$padata .= 	'&PAYMENTREQUEST_0_TAXAMT='.urlencode($charges['TotalTaxAmount']);
				$padata .= 	'&PAYMENTREQUEST_0_AMT='.urlencode($this->paypal->GetGrandTotal($products, $charges));


				$httpParsedResponseAr = $this->paypal->PPHttpPost('DoExpressCheckoutPayment', $padata);
					
				$Currentseller = $this->seller->load($CustomerMail,'email');

				if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]) || 'Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
					//echo $this->planchoose;exit();
					if($this->planchoose == 'standard')
					{
						$Currentseller->setRegisterPlanId($this->planchoose)->setRegisterProductNumber(20);
					}

					if($this->planchoose == 'advanced')
					{
						$Currentseller->setRegisterPlanId($this->planchoose)->setRegisterProductNumber(50);
					}

					if($this->planchoose == 'premium')
					{
						$Currentseller->setRegisterPlanId($this->planchoose)->setRegisterProductNumber(100);
					}


					$Currentseller->setStatus(1)->save();

					$transection = 'Your Transaction ID : '.urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
					$this->message->addNotice(__($transection));
					$this->_redirect ('marketplace/catalog/dashboard');
					
				}

				elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
						
						$messageAlert = '<div style="color:red">Transaction Complete, but payment may still be pending! '.
						'If that\'s the case, You can manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';

						$Currentseller->setStatus(1)->save();

						$this->message->addNotice(__($messageAlert));
					    $this->_redirect ('marketplace/catalog/dashboard');

					}

				else{
						
					$errormssg = '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
					
					$this->message->addNotice(__($errormssg));

					$Currentseller = $this->seller->load($CustomerMail,'email');

					$Currentseller->setStatus(0)->save();

					$this->_redirect ('marketplace/catalog/dashboard');
				}




            
        }
        
        
        
    }