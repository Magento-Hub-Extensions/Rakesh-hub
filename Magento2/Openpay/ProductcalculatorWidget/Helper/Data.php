<?php

namespace Openpay\ProductcalculatorWidget\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;
 
class Data
{
		/**
	   	* @var \Magento\Framework\App\Config\ScopeConfigInterface
	   	*/
	   	protected $scopeConfig;
	   	
		public function __construct(ScopeConfigInterface $scopeConfig){
			$this->scopeConfig = $scopeConfig;
		}
       	public function isEnabled()
       	{
            return $this->getConfig('openpay_widget/active_display/scope') && $this->getConfig('openpay_widget/pricecalculator_details/enable_productcalculator');
       	}
       	public function getConfig($config_path)
	    {
	        return $this->scopeConfig->getValue(
	            $config_path,
	            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
	        );
	    }
}