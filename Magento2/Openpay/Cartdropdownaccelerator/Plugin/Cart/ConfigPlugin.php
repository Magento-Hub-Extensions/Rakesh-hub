<?php

namespace Openpay\Cartdropdownaccelerator\Plugin\Cart;


class ConfigPlugin
{
    
    protected $_assetRepo;
    protected $_scopeConfig;
	protected $_storemanager;

public function __construct(
     \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Framework\View\Asset\Repository $assetRepo
 
)
{
   
    $this->_assetRepo = $assetRepo;
    $this->_scopeConfig = $context->getScopeConfig();
	$this->_storemanager =$context->getStoreManager();
  
}

public function getConfig($config_path){
		return $this->_scopeConfig->getValue(
			$config_path,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
	}
    
    
    public function currencycode(){
		
		return $this->_storemanager->getStore()->getCurrentCurrency()->getCurrencySymbol();
		
	}
    
    
    
    public function getMinprice(){
	    return $this->getConfig('payment/openpay/min_order_total') ;
	}
	
	public function cartaccelaratorstatus(){
	    return $this->getConfig('openpay_widget/cartdropdown_details/enable_productcalculator') ;
	}

	public function isEnabled()	{
	    return $this->getConfig('openpay_widget/active_display/scope') ;
	}

 public function getOpenpayWidgetImage()
    {
        return $this->_assetRepo->getUrl('Openpay_Cartdropdownaccelerator::images/icon-openpay.png');
    }
    
    
    
    /**
     * @param \Magento\Checkout\Block\Cart\Sidebar $subject
     * @param array $result
     * @return array
     */
    public function afterGetConfig(
        \Magento\Checkout\Block\Cart\Sidebar $subject,
        array $result
    )
    {
       if($this->cartaccelaratorstatus() && $this->isEnabled()):
        $result['openpayaccelaratorimage'] = $this->getOpenpayWidgetImage();
        $result['openpaywidgetminprice'] = $this->getMinprice();
        $result['currencycodeopenpay'] = $this->currencycode();
       endif;
        
        return $result;
    }
    
   
     
}