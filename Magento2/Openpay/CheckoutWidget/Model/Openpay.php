<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Openpay\CheckoutWidget\Model;

/**
 * Openpay payment method model
 *
 * @method \Magento\Quote\Api\Data\PaymentMethodExtensionInterface getExtensionAttributes()
 */
class Openpay extends \Aopen\Openpay\Model\Openpay
{
    protected $_objmanager;
    protected $_title;
    protected $_status;
	
    public function objmanager(){
	
     $this->_objmanager =  \Magento\Framework\App\ObjectManager::getInstance();
     return $this->_objmanager;
	
    }
    
    public function checkoutwidgetTitle(){
     $this->_title = $this->objmanager()->create('Openpay\CheckoutWidget\Helper\Data')->getCheckoutWidgetTitle();
     return  $this->_title;
	
    }
    
    public function widgetstatus(){
	 $this->_status = $this->objmanager()->create('Openpay\CheckoutWidget\Helper\Data')->CheckoutWidgetStatus();
	 return $this->_status;
	
    }


     public function getTitle()
    {
	if($this->getCode() == 'openpay' && $this->widgetstatus()){
		return $this->checkoutwidgetTitle();
	}
	else{
	return $this->getConfigData('title');
	}
       
    }
    
   
    
}
