<?php
class Openpay_Checkoutwidget_Helper_Data extends Mage_Core_Helper_Abstract
{


public function managelabelwidget(){
	
$managelabel = Mage::getStoreConfig('widget_setup/checkoutcalculator/manage_checkout_widget_label',Mage::app()->getStore());
return $managelabel;

}


public function managewidgetimage(){
	$imagepath = Mage::getStoreConfig('widget_setup/checkoutcalculator/checkout_widget_imagefield',Mage::app()->getStore());
	$imagefullpath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'uploaddir/'.$imagepath;
	return $imagefullpath;
}
		
public function enablecalculator(){
	return Mage::getStoreConfig('widget_setup/openpayconfig/openpay_enable',Mage::app()->getStore());
}
public function enableprocalculator(){
	
	return Mage::getStoreConfig('widget_setup/checkoutcalculator/checkoutcal_status',Mage::app()->getStore());
}


public function deposite_percentage(){
	$deposit_percentage = Mage::getStoreConfig('widget_setup/checkoutcalculator/deposit_percentage',Mage::app()->getStore());
	return $deposit_percentage;
}
public function payofmonth(){
	$pay_of_month = Mage::getStoreConfig('widget_setup/checkoutcalculator/pay_of_month',Mage::app()->getStore());
	return $pay_of_month;
}
public function currentsubtotal(){
	
	$quote = Mage::getSingleton('checkout/session')->getQuote();
	$subtotal = $quote->getSubtotal();
	return $subtotal;
	
}


	
public function depositePrice(){
	
	
	$product_price = $this->currentsubtotal();
	$deposit_percentage = $this->deposite_percentage(); 
	$deposite_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format(($product_price*$deposit_percentage)/100,2,'.','');
	return $deposite_amount;
	
	
	
}

public function getweeklydata(){
	
	$product_price = $this->currentsubtotal();
	$deposite_amount = $this->depositePrice();
	$pay_of_month = $this->payofmonth();
	
	$rest_amount = preg_replace( '/[^0-9,"."]/', '', $product_price) - preg_replace( '/[^0-9,"."]/', '', $deposite_amount);
	
	
	$html = '';
	//echo $weekly_rest_amount;exit;			
	if($pay_of_month>0){
		
	for($i=2; $i<=($pay_of_month+1); $i++){
			
		$weekly = $i * 4; 
		$weekly_rest_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format($rest_amount/$weekly,2,'.','');
		 $html .= '<div class="price-sec">
			<ul>
				<li class="month"><span>'.$i.'</span>months</li>
				<li class="payment">'. $weekly .' payments</li>
				<li class="price-box">'.$weekly_rest_amount.'*</li>
			</ul>
			 </div>';
			
		}
		
		return $html;
		
	}
	else{
		return '';
	}
					
				
			
	
}





public function getfortnightlydata(){
	
	$product_price = $this->currentsubtotal();
	$deposite_amount = $this->depositePrice();
	$pay_of_month = $this->payofmonth();
	
	$rest_amount = preg_replace( '/[^0-9,"."]/', '', $product_price) - preg_replace( '/[^0-9,"."]/', '', $deposite_amount);
	
	$html = '';
	//echo $weekly_rest_amount;exit;			
	if($pay_of_month>0){
		
		for($i=2; $i<=($pay_of_month+1); $i++){
			
			
		$fortnight = $i * 2;
		$fortnight_rest_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format($rest_amount/$fortnight,2,'.','');
		
		 $html .= '<div class="price-sec">
			<ul>
				<li class="month"><span>'.$i.'</span>months</li>
				<li class="payment">'. $fortnight .' payments</li>
				<li class="price-box">'.$fortnight_rest_amount.'*</li>
			</ul>
			 </div>';
			
		}
		
		return $html;
		
	}
	else{
		return '';
	}
	

}


public function getmonthlydata(){
	
	$product_price = $this->currentsubtotal();
	$deposite_amount = $this->depositePrice();
	$pay_of_month = $this->payofmonth();
	
	$rest_amount = preg_replace( '/[^0-9,"."]/', '', $product_price) - preg_replace( '/[^0-9,"."]/', '', $deposite_amount);
	
	
	$html = '';
	//echo $weekly_rest_amount;exit;			
	if($pay_of_month>0){
		
		for($i=2; $i<=($pay_of_month+1); $i++){
			
		$monthly = $i;
		
		$monthly_rest_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format($rest_amount/$monthly,2,'.','');	
			
		 $html .= '<div class="price-sec">
			<ul>
				<li class="month"><span>'.$i.'</span>months</li>
				<li class="payment">'. $monthly .' payments</li>
				<li class="price-box">'.$monthly_rest_amount.'*</li>
			</ul>
			 </div>';
			
		}
		
		return $html;
		
	}
	else{
		return '';
	}
	
	
}


	public function additionalprice(){
		
		if($this->getminprice() > $this->currentsubtotal())
		{
			$this->_sum = ($this->getminprice()-$this->currentsubtotal());
			
			 return Mage::helper('core')->currency($this->_sum, true, false);
			
			
		}
		
	 }

	public function getminprice(){
	
		//return Mage::getStoreConfig('widget_setup/cart_dropdown/cart_dropdown_min',Mage::app()->getStore());
		return Mage::getStoreConfig('payment/openpay/min_order_total',Mage::app()->getStore());
	
	}
	
	
}
	 