<?php   
class Openpay_Checkoutwidget_Block_Index extends Mage_Payment_Block_Form{   

	
	
	protected function _construct(){
		parent::_construct();

		$this->setTemplate('checkoutwidget/index.phtml');
	}


}