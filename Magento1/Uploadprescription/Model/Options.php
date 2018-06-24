<?php
class Loritel_Uploadprescription_Model_Options
{
  /**
   * Provide available options as a value/label array
   *
   * @return array
   */
  public function toOptionArray()
  {


    $paramId = Mage::app()->getRequest()->getParam('id');

    $uploadPrescriptionModel = Mage::getModel('uploadprescription/uploadprescription')->load($paramId);

    $customerMail = $uploadPrescriptionModel->getCustomerMail();
    $customer = Mage::getModel("customer/customer"); 
    $customer->setWebsiteId(1); 
    $customer->loadByEmail($customerMail); 
    $customerId = $customer->getId();
    $Imagepath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'uploadprescription'.DS.'uploadprescription'.DS.$customerId;
    $path = Mage::getBaseDir('media'). DS . 'uploadprescription'.DS.'uploadprescription'.DS.$customerId;

    $images = glob("$path/*.{jpg,png,bmp,jpeg}", GLOB_BRACE);
    $returnOption = array();
    foreach ($images as $key => $image) {
      $fileLabel = pathinfo($Imagepath.DS.basename($image), PATHINFO_FILENAME);
      $fileValue = basename($image);
      $returnOption[] = array('value'=>$fileValue, 'label'=>$fileLabel);
    }
    return $returnOption;
  }
}