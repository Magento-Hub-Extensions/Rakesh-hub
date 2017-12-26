<?php


class Openpay_Checkoutwidget_Block_Onepage_Payment_Methods extends Mage_Checkout_Block_Onepage_Payment_Methods
{
	
 public function getMethodTitle(Mage_Payment_Model_Method_Abstract $method)
    {
        $form = $this->getChild('payment.method.' . $method->getCode());
        if ($form && $form->hasMethodTitle()) {
            return $form->getMethodTitle();
        }
        
        if($method->getCode() == 'openpay' && $this->enablecheckcal() ){
        return $this->getcheckoutwidgelabel();
        }
        else{
									return $method->getTitle();
        }
    }
    
     public function getMethodLabelAfterHtml(Mage_Payment_Model_Method_Abstract $method)
    {
											if ($form = $this->getChild('payment.method.' . $method->getCode())) {
											
												if($method->getCode() == 'openpay' && $this->enablecheckcal()){
																					return $this->getImagewidget();
												}
												else{
													return $form->getMethodLabelAfterHtml();
												}
																		}
    }
    
    public function getImagewidget(){
    $imagefullpath = Mage::helper('checkoutwidget')->managewidgetimage();
    return  '<img src="'.$imagefullpath.'" alt="widget image" style="margin-top: 5px;"/>';

	
    }
    
    public function getcheckoutwidgelabel(){
	
       return Mage::helper('checkoutwidget')->managelabelwidget();
    }
				
				public function enablecheckcal(){
					 return Mage::helper('checkoutwidget')->enableprocalculator();
					
				}
   
}