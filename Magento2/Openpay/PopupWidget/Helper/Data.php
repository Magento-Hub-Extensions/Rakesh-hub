<?php

namespace Openpay\PopupWidget\Helper;

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
            return $this->getConfig('openpay_widget/active_display/scope');
       	}
       	public function getConfig($config_path)
	    {
	        return $this->scopeConfig->getValue(
	            $config_path,
	            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
	        );
	    }
}