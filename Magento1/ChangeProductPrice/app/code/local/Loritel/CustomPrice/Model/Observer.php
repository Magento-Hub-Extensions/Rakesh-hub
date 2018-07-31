<?php
class Loritel_CustomPrice_Model_Observer
{
	public function updateprice(Varien_Event_Observer $observer)
	{
		//echo 'h222';exit();

		$customer_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
		$customer  = Mage::getModel('customer/customer')->load($customer_id);
		if($customer->getPrimeCustomer() == 232)
		{

			$quote = Mage::getSingleton('checkout/session')->getQuote(); 
			$primeIdwithPrice = array();
			$primeId = array();
			foreach($quote->getAllItems() as $item){
				$productId = $item->getProductId();
				$objProduct = Mage::getModel('catalog/product')->load($productId);
				if($objProduct->getPrimePrice())
				{
					$primePrice = $objProduct->getPrimePrice();
					$primeIdwithPrice[] = array("item_id"=>$item->getId(), "prime_price"=>$primePrice);
					$primeId[] = $item->getId();

				}
		
			}



			if(count($primeId) > 0)
			{

					foreach($quote->getAllItems() as $item){

						if(in_array($item->getId(), $primeId)){

							foreach($primeIdwithPrice as $_primeIdwithPrice)
							{
								$storeId = Mage::app()->getStore()->getStoreId();
								$quoteItem = Mage::getModel('sales/quote_item')->load($_primeIdwithPrice['item_id']);
								//$quoteItem->setWalletAmount($_primeIdwithPrice['prime_price']);
								$product = Mage::getModel('catalog/product')
								->setStoreId($quoteItem->getStoreId())
								->load($quoteItem->getProductId());
								$quoteItem->setProduct($product);
								$quoteItem->setCustomPrice($_primeIdwithPrice['prime_price']);
								$quoteItem->setOriginalCustomPrice($_primeIdwithPrice['prime_price']);
								$quoteItem->getProduct()->setIsSuperMode(true);
								$quoteItem->save();
							}

						}

				}
			}

			$quote1 = Mage::getSingleton('checkout/session')->getQuote(); 
			$quote1->setTotalsCollectedFlag(false)->collectTotals();

		}

	}

}