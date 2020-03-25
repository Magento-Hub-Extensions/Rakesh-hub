<?php
namespace Roche\LoginWithoutPass\Observer;

class Withoutpass implements \Magento\Framework\Event\ObserverInterface
{

  private $_customer;
  private $_customerSession;
  protected $_request;
  protected $_storeManager;
  protected $messageManager;
  protected $responseFactory;
  protected $redirect;


  public function __construct(
  	\Magento\Customer\Model\Customer $customer,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Framework\App\Request\Http $request,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Magento\Framework\App\ResponseFactory $responseFactory,
    \Magento\Framework\App\Response\RedirectInterface $redirect
  ){

  	$this->_customer = $customer;
  	$this->_customerSession = $customerSession;
  	$this->_request = $request;
  	$this->_storeManager = $storeManager;
    $this->messageManager = $messageManager;
    $this->responseFactory = $responseFactory;
    $this->redirect = $redirect;
  }

  public function execute(\Magento\Framework\Event\Observer $observer)
  {
  	 if ($this->_request->isPost()) {
  	 		 $login = $this->_request->getPost('login');
		     if(array_key_exists("username",$login) && !empty($login['username'])){
		     	
		     	$websiteId =$this->_storeManager->getStore()->getWebsiteId();
          try{
            $customer = $this->_customer->setWebsiteId($websiteId)->loadByEmail($login['username']); 
            $this->_customerSession->setCustomerAsLoggedIn($customer);
          }catch(\Exception $e){
            $message = "Please provide currect Email Address";
            $this->messageManager->addError($message);
            return $redirectUrl = $this->redirect->getRedirectUrl();
          }
		     	
		     	
		     }
		     
     }

     return $this;
  }
}