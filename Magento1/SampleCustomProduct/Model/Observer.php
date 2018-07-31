<?php
class DAPL_SampleCustomProduct_Model_Observer
{
	public function updateSampleItem(Varien_Event_Observer $observer)
	{

		//$quote = $observer->getQuote();
		$quote = Mage::getSingleton('checkout/session')->getQuote(); 
		$sampleId = array();
		$sampleIds = array();
		$quotient = 0;
		$remainder = 0;
		$new_price = 1;
		foreach($quote->getAllItems() as $item){
			$productId = $item->getProductId();
			$objProduct = Mage::getModel('catalog/product')->load($productId);
			if($objProduct->getIsSampleProduct() == 1 )
			{
				$sampleId[] = $item->getId();
			}
		
		}

		if(count($sampleId)>4){
			$quotient = count($sampleId)/5;
			$remainder = count($sampleId)%5;
			$sampleIds = array_slice($sampleId, 0, count($sampleId)-$remainder); 

			foreach($quote->getAllItems() as $item){
				if(in_array($item->getId(), $sampleIds)){
					$item->setCustomPrice($new_price);
					$item->setOriginalCustomPrice($new_price);
				}
			}
			$quote1 = Mage::getSingleton('checkout/session')->getQuote(); 
			$quote1->setTotalsCollectedFlag(false)->collectTotals();
		}
		else{
			foreach($quote->getAllItems() as $item){
			$productId = $item->getProductId();
			$objProduct = Mage::getModel('catalog/product')->load($productId);
				if(in_array($item->getId(), $sampleId)){
					$item->setCustomPrice($objProduct->getFinalPrice());
					$item->setOriginalCustomPrice($objProduct->getFinalPrice());
				}
			}
			$quote1 = Mage::getSingleton('checkout/session')->getQuote(); 
			$quote1->setTotalsCollectedFlag(false)->collectTotals();
		}
			
	}
}
