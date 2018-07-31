<?php
class Loritel_Uploadprescription_Block_Adminhtml_Uploadprescription_Edit_Tab_Orderimage extends Mage_Adminhtml_Block_Widget_Form
{
	 public function __construct()
    {
        parent::__construct();
        $this->setTemplate('uploadprescription/orderimage.phtml');
    }

    public function getOrderImage()
    {

    	$id = $this->getRequest()->getParam("id");
		$model = Mage::getModel("uploadprescription/uploadprescription")->load($id);
		$customerMail = $model->getCustomerMail();
         
        $customer = Mage::getModel("customer/customer"); 
        $customer->setWebsiteId(1); 
        $customer->loadByEmail($customerMail); 
        //echo '<pre>';print_r($customer->getData());exit();
        $customerId = $customer->getId();
      
        $Imagepath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'uploadprescription'.DS.'uploadprescription'.DS.$customerId;
        $path = Mage::getBaseDir('media'). DS . 'uploadprescription'.DS.'uploadprescription'.DS.$customerId;
        $response = array();

        $images = glob("$path/*.{jpg,png,bmp,jpeg}", GLOB_BRACE);

        foreach($images as $image)
        {
        $fileName = pathinfo($Imagepath.DS.basename($image), PATHINFO_FILENAME);
        $response[] = '<div class="orderprescrip" ><div class="bounditem"><a data-fancybox="images" href="' .  $Imagepath.DS.basename($image) . '"  data-caption="' . $fileName . '" ><img data-itemId="' . basename($image) . '" class="orderItem" src="' .  $Imagepath.DS.basename($image) . '" /></a></div></div>';
        }
        return $response;

    }

}