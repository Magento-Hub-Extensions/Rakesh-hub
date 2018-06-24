<?php

class Loritel_Uploadprescription_Block_Adminhtml_Uploadprescription_UploadimageRender extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{	

			public function render(Varien_Object $row)
			{
					 $imageName =  $row->getData($this->getColumn()->getIndex());
					$email =  $row->getData('customer_mail');
					$customer = Mage::getModel("customer/customer"); 
					$customer->setWebsiteId(1); 
					$customer->loadByEmail($email);
					$imageNameArray = json_decode($imageName, true);

					$path = Mage::getBaseDir('media'). DS . 'uploadprescription'.DS.'uploadprescription'.DS.$customer->getId();
					
					$Imagepath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'uploadprescription'.DS.'uploadprescription'.DS.$customer->getId();
					$imagesrc = array();

					$images = glob("$path/*.{jpg,png,bmp,jpeg}", GLOB_BRACE);

					foreach($images as $image)
					{
					 $imagesrc[] = '<img src="' .  $Imagepath.DS.basename($image) . '" width="80" height="80" />';
					}

					$FinalImagePath = implode(" ",$imagesrc);
					return $FinalImagePath;
			}
}