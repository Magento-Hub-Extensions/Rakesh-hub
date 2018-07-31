<?php
namespace DAPL\Paypalcustompayment\Controller\Seller;
 
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\UrlFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\InputException;
use DAPL\Paypalcustompayment\Helper\Data;

 
 
 
    class CreatePost extends  \Lof\MarketPlace\Controller\Seller\CreatePost
    {
		
		
	protected $urlfactory;
	protected $paypal;
    protected $_sellerHelper;
	
	   
    public function __construct(
        Context $context, 
        Session $customerSession, 
        AccountManagementInterface $accountManagement, 
        UrlFactory $urlFactory, 
        Escaper $escaper, 
        DataObjectHelper $dataObjectHelper, 
        \Lof\MarketPlace\Model\Sender $sender,
        \Lof\MarketPlace\Helper\Data $sellerHelper,
        AccountRedirect $accountRedirect,
		Data $PaypalClass
		) 
    {
        $this->_sellerHelper = $sellerHelper;
		$this->paypal = $PaypalClass;
		$this->urlfactory = $urlFactory->create();
        parent::__construct(
            $context,
            $customerSession,
            $accountManagement,
            $urlFactory,
            $escaper,
            $dataObjectHelper,
            $sender,
            $sellerHelper,
            $accountRedirect
        );
		
    }

       
	 /**
     * Create customer account action
     *
     * @return void @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute() {
		$planvaluecustom = $this->getRequest()->getParam('planvaluecustom');
	    //echo $planvaluecustom.'<br>';
        $resultRedirectFlag = 0;
        $objectModelManager = \Magento\Framework\App\ObjectManager::getInstance ();
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create ();
        
        if ($this->session->isLoggedIn () || ! $objectModelManager->get ( 'Magento\Customer\Model\Registration' )->isAllowed ()) {
            $resultRedirect->setPath ( '*/*/' );
            return $resultRedirect;
        }
        
        if (! $this->getRequest ()->isPost ()) {
            $url = $this->urlModel->getUrl ( '*/*/create', [ 
                    '_secure' => true 
            ] );
            $resultRedirect->setUrl ( $this->_redirect->error ( $url ) );
            return $resultRedirect;
        }
        
        $this->session->regenerateId ();
        
        try {
            $address = $this->extractAddress ();
            $addresses = $address === null ? [ ] : [ 
                    $address 
            ];
            
            $customer = $objectModelManager->get ( 'Magento\Customer\Model\CustomerExtractor' )->extract ( 'customer_account_create', $this->_request );
            $customer->setAddresses ( $addresses );
            
            $password = $this->getRequest ()->getParam ( 'password' );
			
			//echo $password;exit;
			
            $confirmation = $this->getRequest ()->getParam ( 'password_confirmation' );
            $redirectUrl = $this->session->getBeforeAuthUrl ();
            
            $this->checkPasswordConfirmation ( $password, $confirmation );
            
            $customer = $this->accountManagement->createAccount ( $customer, $password, $redirectUrl );
            
            if ($this->getRequest ()->getParam ( 'is_subscribed', false )) {
                $objectModelManager->get ( 'Magento\Newsletter\Model\SubscriberFactory' )->create ()->subscribeCustomerById ( $customer->getId () );
            }
            
            $this->_eventManager->dispatch ( 'customer_register_success', [ 
                    'account_controller' => $this,
                    'customer' => $customer 
            ] );
            
            $confirmationStatus = $this->accountManagement->getConfirmationStatus ( $customer->getId () );
            if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                $email = $objectModelManager->get ( 'Magento\Customer\Model\Url' )->getEmailConfirmationUrl ( $customer->getEmail () );
                
                $this->messageManager->addSuccess ( __ ( 'You must confirm your account. Please check your email for the confirmation link or <a href="%1">click here</a> for a new link.', $email ) );
                
                $url = $this->urlModel->getUrl ( '*/*/index', [ 
                        '_secure' => true 
                ] );
                $resultRedirect->setUrl ( $this->_redirect->success ( $url ) );
            } else {
                $this->session->setCustomerDataAsLoggedIn ( $customer );
                $this->messageManager->addSuccess ( $this->getSuccessMessage () );
                $resultRedirect = $this->accountRedirect->getRedirect ();
                $url = $this->urlModel->getUrl ( 'customer/account', [ 
                        '_secure' => true 
                ] );
                $resultRedirect->setUrl ( $this->_redirect->success ( $url ) );
            }
            $resultRedirectFlag = 1;          
        } catch ( StateException $e ) {
            $url = $this->urlModel->getUrl ( 'customer/account/forgotpassword' );
            
            $message = __ ( 'There is already an account with this email address. If you are sure that it is your email address, <a href="%1">click here</a> to get your password and access your account.', $url );
            
            $this->messageManager->addError ( $message );
        } catch ( InputException $e ) {
            $this->messageManager->addError ( $this->escaper->escapeHtml ( $e->getMessage () ) );
            foreach ( $e->getErrors () as $error ) {
                $this->messageManager->addError ( $this->escaper->escapeHtml ( $error->getMessage () ) );
            }
        } catch ( \Exception $e ) {
            //$this->messageManager->addException ( $e, __ ( 'We can\'t save the customer.' ) );
        }
        if($resultRedirectFlag == 0){
        $this->session->setCustomerFormData ( $this->getRequest ()->getPostValue () );
        $defaultUrl = $this->urlModel->getUrl ( '*/*/create', [ 
                '_secure' => true 
        ] );        
        $resultRedirect->setUrl ( $this->_redirect->error ( $defaultUrl ) );
        }
    
        $url                = $this->getRequest()->getPost('url');
        $group              = $this->getRequest()->getPost('group');
        $layout             = "2columns-left";
        $stores = array();
        $stores[] = $this->_sellerHelper->getCurrentStoreId();
         
        $objectManager      = \Magento\Framework\App\ObjectManager::getInstance ();
        $customerSession    = $objectManager->get('Magento\Customer\Model\Session');
        
        if ($customerSession->isLoggedIn()) {

            $customerId     = $customerSession->getId ();
            $customerObject = $customerSession->getCustomer ();
            $customerEmail  = $customerObject->getEmail ();
            $customerName   = $customerObject->getName();
            $sellerApproval = $this->_sellerHelper->getConfig('general_settings/seller_approval');
           
            if ($sellerApproval) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $sellerModel = $objectManager->get('Lof\MarketPlace\Model\Seller');
                try {
                    $sellerModel->setName($customerName)->setEmail($customerEmail)->setStatus(0)->setGroupId($group)->setCustomerId($customerId)->setStores($stores)->setUrlKey($url)->setPageLayout($layout)->save();
                    $this->_redirect ('lofmarketplace/seller/becomeseller/approval/0');
                }  catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                     $this->_redirect ('lofmarketplace/seller/becomeseller');
                } 
            } else {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $sellerModel = $objectManager->get('Lof\MarketPlace\Model\Seller');
                try {
                    $sellerModel->setName($customerName)->setEmail($customerEmail)->setStatus(1)->setGroupId($group)->setCustomerId($customerId)->setUrlKey($url)->save();
					
                    $products = [];

                if($planvaluecustom != 'free')
                {

    				if($planvaluecustom == 'standard')
    				{
    					
    					
    					
    					$products[0]['ItemName'] = $planvaluecustom; //Item Name
    					$products[0]['ItemPrice'] = 49.99; //Item Price
    					$products[0]['ItemQty']	= 1; // Item Quantity
    				
    					
    				}
               
                    if($planvaluecustom == 'advanced')
                    {
                       
                       
                        $products[0]['ItemName'] = $planvaluecustom; //Item Name
                        $products[0]['ItemPrice'] = 99.99; //Item Price
                        $products[0]['ItemQty'] = 1; // Item Quantity
                    }

                    if($planvaluecustom == 'premium')
                    {
                       
                       
                        $products[0]['ItemName'] = $planvaluecustom; //Item Name
                        $products[0]['ItemPrice'] = 199.99; //Item Price
                        $products[0]['ItemQty'] = 1; // Item Quantity
                    }

                    $charges = [];


                    $charges['TotalTaxAmount'] = 0; 

                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $paypalSession = $objectManager->create('Magento\Customer\Model\Session');
                    $paypalSession->setProductDetails($products);
                    $paypalSession->setProductCharges($charges);

                    $httpParsedResponseAr = $this->paypal->SetExpressCheckOut($products, $charges);
                }
                else{
                         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                         $currentseller = $objectManager->create('Lof\MarketPlace\Model\Seller')->load($this->_sellerHelper->getSellerId());

                         $currentseller->setRegisterPlanId($planvaluecustom)->setRegisterProductNumber(5)->save();
                        //echo $planvaluecustom;exit();
                         $this->_redirect ('marketplace/catalog/dashboard');

                }
					
					

                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                     $this->_redirect ('lofmarketplace/seller/becomeseller');
                }
            }

            if($this->_sellerHelper->getConfig('email_settings/enable_send_email')) {
                $data = [];
                $data['name'] = $customerName;
                $data['email'] = $customerEmail;
                $data['group'] = $group;
                $data['url'] = $sellerModel->getUrl();
                $this->sender->registerSeller($data);
            } 
     
        } else {
            $resultRedirect = $this->resultRedirectFactory->create ();
            $resultRedirect->setPath('customer/account/login/');
            return $resultRedirect;
        }



    }
	  
    
    }