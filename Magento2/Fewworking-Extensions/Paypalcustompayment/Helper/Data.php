<?php
namespace DAPL\Paypalcustompayment\Helper;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Asset\Repository;


	class Data  {

		
		protected $_storeManager;

		protected $_assetRepo;
		protected $_scopeConfig;
		
			public function __construct(
			 Context  $context,
			 Repository $assetRepo
				
			)
			{
					$this->_assetRepo = $assetRepo;
					$this->_scopeConfig = $context->getScopeConfig();
					$this->_storeManager =$context->getStoreManager();
			}


		public function getConfig($config_path){
			return $this->_scopeConfig->getValue(
				$config_path,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
	     }


	   public function getpaypalApiUserName(){
	
		$apiUserName = $this->getConfig('paypal/wpp/api_username');
		return $apiUserName;
	    
	   }

	   public function getapiPassword()
	   {
	   	$apiPassword = $this->getConfig('paypal/wpp/api_password');
		return $apiPassword;
	   }
	   public function getapiSignature()
	   {
	   	$apiSignature = $this->getConfig('paypal/wpp/api_signature');
		return $apiSignature;
	   }

	   public function getsandboxFlag()
	   {
		   	$sandboxFlag = $this->getConfig('paypal/wpp/sandbox_flag');
		   	if($sandboxFlag == 1)
		   	{
		   		return '.sandbox';
		   	}
		   	if($sandboxFlag == 0)
		   	{
		   		return '';
		   	}

		
	   }




		
		function GetItemTotalPrice($item){
			return $item['ItemPrice'] * $item['ItemQty']; 
		}
		
		function GetProductsTotalAmount($products){
		
			$ProductsTotalAmount=0;

			foreach($products as $p => $item){
				
				$ProductsTotalAmount = $ProductsTotalAmount + $this -> GetItemTotalPrice($item);	
			}
			
			return $ProductsTotalAmount;
		}
		
		function GetGrandTotal($products, $charges){
			
			//Grand total including all tax, insurance, shipping cost and discount
			
			$GrandTotal = $this -> GetProductsTotalAmount($products);
			
			foreach($charges as $charge){
				
				$GrandTotal = $GrandTotal + $charge;
			}
			
			return $GrandTotal;
		}
		
		function SetExpressCheckout($products, $charges, $noshipping='1'){
			
			//Parameters for SetExpressCheckout, which will be sent to PayPal
			$returnUrl = $this->_storeManager->getStore()->getBaseUrl().'paypalcustompayment/seller/returnpaypal';
			$cancelUrl =$this->_storeManager->getStore()->getBaseUrl().'paypalcustompayment/seller/cancelurl';
			
			
			
			$padata  = 	'&METHOD=SetExpressCheckout';
			
			$padata .= 	'&RETURNURL='.urlencode($returnUrl);
			$padata .=	'&CANCELURL='.urlencode($cancelUrl);
			$padata .=	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
			
			foreach($products as $p => $item){
				
				$padata .=	'&L_PAYMENTREQUEST_0_NAME'.$p.'='.urlencode($item['ItemName']);
				$padata .=	'&L_PAYMENTREQUEST_0_AMT'.$p.'='.urlencode($item['ItemPrice']);
				$padata .=	'&L_PAYMENTREQUEST_0_QTY'.$p.'='. urlencode($item['ItemQty']);
			}		

			
						
			$padata .=	'&NOSHIPPING='.$noshipping; //set 1 to hide buyer's shipping address, in-case products that does not require shipping
						
			$padata .=	'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($this -> GetProductsTotalAmount($products));
			
			$padata .=	'&PAYMENTREQUEST_0_TAXAMT='.urlencode($charges['TotalTaxAmount']);
			$padata .=	'&PAYMENTREQUEST_0_AMT='.urlencode($this->GetGrandTotal($products, $charges));
			
			
			
			$httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $padata);
			
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){

				//$paypalmode = '.sandbox';

				$paypalmode = $this->getsandboxFlag();
			
				//Redirect user to PayPal store with Token received.
				
				$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
				
				header('Location: '.$paypalurl);
			}
			else{
				
				//Show error message
				
				echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				
				echo '<pre>';
					
					print_r($httpParsedResponseAr);
				
				echo '</pre>';
			}	
		}		
		
			
		
				
		
		
		function PPHttpPost($methodName_, $nvpStr_) {
 
			    $API_UserName = urlencode($this->getpaypalApiUserName());
				$API_Password = urlencode($this->getapiPassword());
				$API_Signature = urlencode($this->getapiSignature());
				// Set up your API credentials, PayPal end point, and API version.
				// $API_UserName = urlencode('dipankar.pay_api1.gmail.com');
				// $API_Password = urlencode('NVMBT9LH8LCKU7LZ');
				// $API_Signature = urlencode('AdcR3nDSg3758edzxfstm2wATUK-AG0mHch7j9TvTHh-SVI5MUDcY.RS');
				
				//$paypalmode = '.sandbox';
				$paypalmode = $this->getsandboxFlag();
		
				$API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
				$version = urlencode('109.0');
			
				// Set the curl parameters.
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
				curl_setopt($ch, CURLOPT_VERBOSE, 1);
				//curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
				
				// Turn off the server and peer verification (TrustManager Concept).
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
			
				// Set the API operation, version, and API signature in the request.
				$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
			
				// Set the request as a POST FIELD for curl.
				curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
			
				// Get response from the server.
				$httpResponse = curl_exec($ch);
			
				if(!$httpResponse) {
					exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
				}
			
				// Extract the response details.
				$httpResponseAr = explode("&", $httpResponse);
			
				$httpParsedResponseAr = array();
				foreach ($httpResponseAr as $i => $value) {
					
					$tmpAr = explode("=", $value);
					
					if(sizeof($tmpAr) > 1) {
						
						$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
					}
				}
			
				if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
					
					exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
				}
			
			return $httpParsedResponseAr;
		}



	}
