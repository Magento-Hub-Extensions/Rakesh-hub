<?php   
class Openpay_Cartdropdownaccelerator_Block_Index extends Mage_Core_Block_Template{   

protected $_subtotalprice;
protected $_additionalprice;
protected $_sum;


public function enablecalculator(){
	return Mage::getStoreConfig('widget_setup/openpayconfig/openpay_enable',Mage::app()->getStore());
}

public function getminprice(){
	
//return Mage::getStoreConfig('widget_setup/cart_dropdown/cart_dropdown_min',Mage::app()->getStore());
	return Mage::getStoreConfig('payment/openpay/min_order_total',Mage::app()->getStore());
	
}



 public function enablecartacceslarator(){

return Mage::getStoreConfig('widget_setup/cart_dropdown/cart_dropdown_status',Mage::app()->getStore());	
	
 }
 
 public function getcartsubtotal(){
	

  $this->_subtotalprice =  Mage::getSingleton('checkout/session')->getQuote()->getSubtotal();
  
  
  return $this->_subtotalprice;
 
  

 }
 
 
 public function additionalprice(){
	
	if($this->getminprice() > $this->getcartsubtotal())
	{
		$this->_sum = ($this->getminprice()-$this->getcartsubtotal());
		
		 return Mage::helper('core')->currency($this->_sum, true, false);
		
		
	}
	
	
	
	
 }





}