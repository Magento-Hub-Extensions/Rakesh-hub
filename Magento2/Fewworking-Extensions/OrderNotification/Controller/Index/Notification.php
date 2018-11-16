<?php
namespace Ueg\OrderNotification\Controller\Index;
use Ueg\OrderNotification\Cookie\CustomCookie;

class Notification extends \Magento\Framework\App\Action\Action
{
	protected $resultJsonFactory;



	protected $orderCollectionFactory;
	protected $_cookieManagerInterface;

	protected $orderConfig;

	protected $orderFactory;

	protected $productFactory;

	protected $date;

	protected $helperdata;

	protected $_image;

	protected $countryFactory;

	protected $uegHelper;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
		\Magento\Sales\Model\OrderFactory $orderFactory,
		\Magento\Sales\Model\Order\Config $orderConfig,
		\Magento\Framework\Stdlib\DateTime $date,
		\Magento\Catalog\Model\ProductFactory $productFactory,
		\Magento\Framework\Pricing\Helper\Data $helperdata,
		\Magento\Directory\Model\CountryFactory $countryFactory,
		\Magento\Catalog\Helper\Image $_image,
		\Ueg\OrderNotification\Helper\Data $uegHelper,
    	CustomCookie $cookieManagerInterface)
	{
		$this->resultJsonFactory = $resultJsonFactory;
		$this->orderCollectionFactory = $orderCollectionFactory;
		$this->orderConfig = $orderConfig;
		$this->orderFactory = $orderFactory;
		$this->date = $date;
		$this->productFactory = $productFactory;
		$this->helperdata = $helperdata;
		$this->_image = $_image;
		$this->countryFactory = $countryFactory;
		$this->_cookieManagerInterface = $cookieManagerInterface;
		$this->uegHelper = $uegHelper;
		parent::__construct($context);
	}

	
    public function getOrder()
    {
        $orders = $this->orderCollectionFactory->create()->addAttributeToSelect(
            '*'
        )->addAttributeToFilter(
            'status',
            ['in' => $this->orderConfig->getVisibleOnFrontStatuses()]
        )->addAttributeToSort(
            'created_at',
            'desc'
        )->getFirstItem();
        return $orders;
    }


    private function getNotifyOrderStatus()
    {
    	if($this->uegHelper->getOrderNotificationStatus() == 0)
    	{
    		return false;
    	}
    	else
    	{
    		return true;
    	}
    }


	public function execute()
	{
		//echo 'Hii';exit();
		$result = $this->resultJsonFactory->create();


		$order = $this->getOrder();
		//echo $order->getId().'sA';exit();
		$orderObject = $this->orderFactory->create()->load($order->getId());

		$shippingAddress = $orderObject->getShippingAddress();

		$region = $shippingAddress->getRegion();
		$city = $shippingAddress->getCity();
		$country = $this->countryFactory->create()->loadByCode($shippingAddress->getCountryId());


		///echo  '<pre>';print_r();exit();

		$finalAddress = $country->getData('iso3_code').' '.$city.' '.$region;

		$orderCreatedAt = $orderObject->getCreatedAt();
		$date = strtotime($orderCreatedAt);


		$orderFormateDate = date('d-M-Y H:i:s', $date);
		
		$orderDate = new \DateTime($orderFormateDate);
		
		$second_date = new \DateTime("now");

		$difference = $second_date->diff($orderDate);
		

		$finalDateTime =  $this->format_interval($difference);

		$allOrderItem = $orderObject->getAllVisibleItems();


		foreach ($allOrderItem as $key => $item) {
				$productId = $item->getProductId();
				$productObject = $this->productFactory->create()->load($productId);
				$images = $this->getProductImageUrl($productObject);

				$price = $this->helperdata->currency(number_format($productObject->getFinalPrice(),2),true,false);
				$JsonResponce = array();
				$html = '';
				$html .= '<div class="recentordernotification">
							<div class="notice-img">
								<a class="notice-product-link" href="'.$productObject->getProductUrl().'"><img src="'.$images.'" alt="'.$productObject->getName().'" title="'.$productObject->getName().'"></a>
							</div>
							<div class="notice-text"> Someone in '.$finalAddress.'  bought <a class="notice-product-link" href="'.$productObject->getProductUrl().'">'.$productObject->getName().'</a> <br> 
								<div class="bottom-line price">'.$price.'</div> 
								<div class="time-ago bottom-line">'.$finalDateTime.' ago</div> <br> '.$shippingAddress->getFirstName()." ".$shippingAddress->getLastName().'
							</div>
							</div>';
				$random = md5(rand(1,1000));
				$this->_cookieManagerInterface->delete('order_security_token');
				$this->_cookieManagerInterface->setCookie($random,444);
				$JsonResponce['msg'] = $html;
				$JsonResponce['token'] = $this->_cookieManagerInterface->getCookie('order_security_token');
				$JsonResponce['update'] = $this->getNotifyOrderStatus();
				$result->setData($JsonResponce);
				
		}

		return $result;

	}


	public function format_interval(\DateInterval $interval) {
		    $result = "";
		    if ($interval->y) { $result .= $interval->format("%y years "); }
		    if ($interval->m) { $result .= $interval->format("%m months "); }
		    if ($interval->d) { $result .= $interval->format("%d days "); }
		    if ($interval->h) { $result .= $interval->format("%h hours "); }
		    if ($interval->i) { $result .= $interval->format("%i minutes "); }
		    if ($interval->s) { $result .= $interval->format("%s seconds "); }

		    return $result;
		}



		public function getProductImageUrl($product)
		{
		    return $this->_image->init($product, 'product_base_image')->constrainOnly(FALSE)
		        ->keepAspectRatio(TRUE)
		        ->keepFrame(FALSE)
		        ->getUrl();
		} 
}