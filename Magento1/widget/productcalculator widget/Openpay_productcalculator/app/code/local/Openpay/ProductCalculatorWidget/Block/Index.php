<?php   
class Openpay_ProductCalculatorWidget_Block_Index extends Mage_Core_Block_Template{
	
	
public function enablecalculator(){
	return Mage::getStoreConfig('widget_setup/openpayconfig/openpay_enable',Mage::app()->getStore());
}
public function enableprocalculator(){
	
	return Mage::getStoreConfig('widget_setup/productcalculator/calculator_enable',Mage::app()->getStore());
}


public function deposite_percentage(){
	$deposit_percentage = Mage::getStoreConfig('widget_setup/productcalculator/deposit_percentage',Mage::app()->getStore());
	return $deposit_percentage;
}
public function payofmonth(){
	$pay_of_month = Mage::getStoreConfig('widget_setup/productcalculator/pay_of_month',Mage::app()->getStore());
	return $pay_of_month;
}
protected function currentProduct(){
	
	$product = Mage::registry('current_product');
	$_product = Mage::getModel('catalog/product')->load($product->getId());
	return $_product;
	
}


	
public function depositePrice(){
	
	$product = $this->currentProduct();
	$product_price = $product->getFinalPrice();
	$deposit_percentage = $this->deposite_percentage(); 
	$deposite_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format(($product_price*$deposit_percentage)/100,2,'.','');
	return $deposite_amount;
	
	
	
}

public function getweeklydata(){
	
	$product = $this->currentProduct();
	$product_price = $product->getFinalPrice();
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
	
	$product = $this->currentProduct();
	$product_price = $product->getFinalPrice();
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
	
	$product = $this->currentProduct();
	$product_price = $product->getFinalPrice();
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



}