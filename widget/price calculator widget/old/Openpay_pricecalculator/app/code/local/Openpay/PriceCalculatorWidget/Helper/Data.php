<?php
class Openpay_PriceCalculatorWidget_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	
	public function getOpenpayPriceCallculationRoundHtml($product){

		if(Mage::getStoreConfig('widget_setup/openpayconfig/openpay_enable',Mage::app()->getStore()) && Mage::getStoreConfig('widget_setup/pricecalculator/product_rollover_option1',Mage::app()->getStore())){
			$product_price =  $product->getFinalPrice();
			$deposit_percentage = Mage::getStoreConfig('widget_setup/pricecalculator/deposit_percentage',Mage::app()->getStore());
			$pay_of_month = Mage::getStoreConfig('widget_setup/pricecalculator/pay_of_month',Mage::app()->getStore());
			$price_calculator_color = Mage::getStoreConfig('widget_setup/pricecalculator/price_calculator_color',Mage::app()->getStore());

			$deposite_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format(($product_price*$deposit_percentage)/100,2,'.','');
			$rest_amount = $product_price - $deposite_amount;
			$weekly = $pay_of_month*4;
			$fortnight = $pay_of_month*2;
			$weekly_rest_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format($rest_amount/$weekly,2,'.','');
			$fortnight_rest_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format($rest_amount/$fortnight,2,'.','');

			$html = '';
			$html .= '<div class="price_calc"><span style="color: #'.$price_calculator_color.'"><img src='.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'openpay-widget/logo-small-dark.png'.' alt="Openpay" ><p> '.$deposite_amount.' today + '.$weekly.' payments of '.$weekly_rest_amount.' in your own time</p><p>More option available at checkout</p></span></div>';

			return $html;
		}else{
			return false;
		}
	}

	public function getOpenpayPriceCallculationHtml($product){

		if(Mage::getStoreConfig('widget_setup/openpayconfig/openpay_enable',Mage::app()->getStore()) && Mage::getStoreConfig('widget_setup/pricecalculator/product_rollover_option2',Mage::app()->getStore())){
			$product_price =  $product->getFinalPrice();
			$deposit_percentage = Mage::getStoreConfig('widget_setup/pricecalculator/deposit_percentage',Mage::app()->getStore());
			$pay_of_month = Mage::getStoreConfig('widget_setup/pricecalculator/pay_of_month',Mage::app()->getStore());
			$price_calculator_color = Mage::getStoreConfig('widget_setup/pricecalculator/price_calculator_color',Mage::app()->getStore());

			$deposite_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format(($product_price*$deposit_percentage)/100,2,'.','');
			$rest_amount = $product_price - $deposite_amount;
			$weekly = $pay_of_month*4;
			$fortnight = $pay_of_month*2;
			$weekly_rest_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format($rest_amount/$weekly,2,'.','');
			$fortnight_rest_amount = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol().number_format($rest_amount/$fortnight,2,'.','');

			$html = '';
			$html .= '<div class="price_calc_box"><span><img src='.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'openpay-widget/logo-small.png'.' alt="Openpay" > available<p> Pay '.$deposite_amount.' today then '.$weekly.' interest free weekly payments <a target="_blank" href="https://www.openpay.com.au/">Learn more</a></p></span></div>';

			return $html;
		}else{
			return false;
		}
	}
}
	 