<?php

namespace Openpay\Cartdropdownaccelerator\Block\Index;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\StoreManagerInterface;


class Index extends \Magento\Framework\View\Element\Template {
	
	protected $_scopeConfig;
	protected $_storemanager;
	
	protected $price;
	protected $_returnvalue;

    public function __construct(\Magento\Catalog\Block\Product\Context $context,ScopeConfigInterface $storeconfig,StoreManagerInterface $storemanager, array $data = [])
	{

	
        parent::__construct($context, $data);
		
		$this->_scopeConfig = $storeconfig;
		$this->_storemanager = $storemanager;
	

    }
	
	public function getConfig($config_path)
	{
		return $this->_scopeConfig->getValue(
			$config_path,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
	}
	public function currencycode(){
		
		return $this->_storemanager->getStore()->getCurrentCurrency()->getCurrencySymbol();
		
	}
	public function minprice()
	{
		
      return $this->getConfig('payment/openpay/min_order_total') ;
	}
	
	public function cartaccelaratorstatus(){
	
	 return $this->getConfig('openpay_widget/cartdropdown_details/enable_productcalculator') ;
	
	}


	 public function isEnabled()
       	{
            return $this->getConfig('openpay_widget/active_display/scope') ;
       	}
		
		
		public function displayprice(){
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 
			 
			$subTotal = $cart->getQuote()->getSubtotal();
			$value = $this->minprice();
			
			if($value > $subTotal &&  $subTotal){
				
				return $this->_returnvalue = $value - $subTotal;
				
				
			}
			
			
			
			
			
		}
		
	
}