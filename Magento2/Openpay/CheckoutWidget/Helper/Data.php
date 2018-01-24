<?php

namespace Openpay\CheckoutWidget\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;

 
class Data
{
		/**
	* @var \Magento\Framework\App\Config\ScopeConfigInterface
	   	*/
	protected $scopeConfig;
	protected $objectManager;
	
	protected $_title;
	protected $_widgetimage;
	protected $_status;
	protected $_imagepathname;
	
	public function __construct(ScopeConfigInterface $scopeConfig,ObjectManagerInterface $objectManager){
		$this->scopeConfig = $scopeConfig;
		$this->objectManager = $objectManager;
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
	
	
	public function getCheckoutWidgetTitle(){
	
	$this->_title = $this->getConfig('openpay_widget/checkout_widget_details/manage_checkoutwidget_label');
	return $this->_title;
	    
		
	}
	
	public function CheckoutWidgetStatus(){
	   
	   $this->_status = $this->getConfig('openpay_widget/checkout_widget_details/enable_checkoutwidget');
	   return $this->_status;
		
		
	}
	
	
	public function getimagepathname(){
		
	    $this->_imagepathname = $this->getConfig('openpay_widget/checkout_widget_details/manage_checkoutwidget_image');
	    return $this->_imagepathname;
		
	}
	
	
	public function getMediaUrl(){

            $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

            return $media_dir;
        }
	
	public function getimagepath(){
		
		
		$this->_widgetimage = $this->getMediaUrl().'openpay/checkoutwidget/'.$this->getimagepathname();
		return $this->_widgetimage;
		
		
		//return 'hii';
	}
	
	
}