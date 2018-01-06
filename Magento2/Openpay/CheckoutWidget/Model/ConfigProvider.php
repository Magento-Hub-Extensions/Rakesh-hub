<?php

namespace Openpay\CheckoutWidget\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\SamplePaymentGateway\Gateway\Http\Client\ClientMock;
use Magento\Framework\View\LayoutInterface;
use Openpay\CheckoutWidget\Helper\Data;


class ConfigProvider implements ConfigProviderInterface
{
    /** @var LayoutInterface  */
    protected $_layout;
	protected $_helper;
	

    public function __construct(LayoutInterface $layout, Data $helper)
    {
        $this->_layout = $layout;
		$this->_helper = $helper;
		
		
    }

    public function getConfig()
    {
        
        $config = array();
       
		
		if($this->checkCheckoutWidget()){
			 $config['mynewconfig'] = $this->_layout->createBlock('Openpay\CheckoutWidget\Block\Index\Index')->setTemplate('Openpay_CheckoutWidget::checkoutwidget_index_index.phtml')->toHtml();
			  $config['imageopenpay'] = $this->getimageUrl();
			 return $config;
		}
		else{
			return $config;
		}
		
       
	

    }
	public function getimageUrl(){
		
		return $this->_helper->getimagepath();
		
	}
	
	public function checkCheckoutWidget(){
		
		return $this->_helper->CheckoutWidgetStatus();
		
	}
	
	
    

    
}