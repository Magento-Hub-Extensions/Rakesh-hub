<?php
class Loritel_Uploadprescription_IndexController extends Mage_Core_Controller_Front_Action{

    public function preDispatch() {
    parent::preDispatch();
    if(!Mage::getSingleton('customer/session')->isLoggedIn()) {
      Mage::getSingleton("checkout/session")->addNotice("Please login to access upload prescription!!");
      $this->_redirectReferer();
     }
  }




    public function IndexAction() {
     // echo 'ggdhg';exit();
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Upload Prescription"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("titlename", array(
                "label" => $this->__("Upload Prescription"),
                "title" => $this->__("Upload Prescription")
		   ));
     // $this->_initLayoutMessages('checkout/session');

      $this->renderLayout(); 
	  
    }

    public function imageuploadAction()
    {


        //echo '<pre>';print_r($_FILES);exit();
        $uploadedFileName = array();
        $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
        $customerId = $currentCustomer->getId();
        foreach ($_FILES['images']['name'] as $key => $image) {

                  try {
                      Mage::log('uploading');
                      $uploader = new Varien_File_Uploader(
                                          array(
                                      'name' => $_FILES['images']['name'][$key],
                                      'type' => $_FILES['images']['type'][$key],
                                      'tmp_name' => $_FILES['images']['tmp_name'][$key],
                                      'error' => $_FILES['images']['error'][$key],
                                      'size' => $_FILES['images']['size'][$key]
                                          )
                                  );

                      // Any extention would work
                      $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                      $uploader->setAllowRenameFiles(true);

                      $uploader->setFilesDispersion(false);

                      
                       $path = Mage::getBaseDir('media') . DS . 'uploadprescription'.DS.'uploadprescription'.DS.$customerId;

                      if (!is_dir($path)) {
                          mkdir($path, 0777, true);
                      }
                      $uploader->save($path, $_FILES['images']['name'][$key]);
                      $uploadedFileName[] = $uploader->getUploadedFileName();
                  } catch (Exception $e) {
                      echo $e->getMessage();
                      Mage::log($e->getMessage());
                  }
              }
              $customerObject = Mage::getModel('customer/customer')->load($customerId);
              $uploadPrescription = array();


              $currentPrescriptionModel = Mage::getModel('uploadprescription/uploadprescription')->load($customerObject->getEmail(),'customer_mail');

              if(!$currentPrescriptionModel->getId())
              {
                    $uploadPrescription['customer_name'] = $customerObject->getFirstname().' '.$customerObject->getLastname();
                    $uploadPrescription['customer_mail'] = $customerObject->getEmail();
                    $uploadPrescription['customer_phonenumber'] = $customerObject->getMobileNumber();
                    $uploadPrescription['prescription_images'] = json_encode($uploadedFileName);
                    $uploadPrescription['clinical_history'] = '';
                    $uploadPrescription['diagnosys'] = '';
                    Mage::getModel('uploadprescription/uploadprescription')->setData($uploadPrescription)->save();
              }
              else
              {
                  $presctionImagesPre = $currentPrescriptionModel->getPrescriptionImages();
                  $presctionImagesPreArray = json_decode($presctionImagesPre, true);
                  if(is_array($presctionImagesPreArray))
                  {
                    $mergerImages = array_merge($presctionImagesPreArray, $uploadedFileName);
                  }
                  $uploadPrescription['prescription_images'] = json_encode($mergerImages);
                  $currentPrescriptionModel->addData($uploadPrescription)->save();
              }
              

       

       Mage::getSingleton('core/session')->addSuccess(Mage::helper('customer')->__('Prescription has been uploaded successfully!!!'));

       $this->_redirect('*/*');

    }


    public function getuploadedimageAction()
    {
      $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
      $customerId = $currentCustomer->getId();
      $Imagepath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'uploadprescription'.DS.'uploadprescription'.DS.$customerId;
      $path = Mage::getBaseDir('media'). DS . 'uploadprescription'.DS.'uploadprescription'.DS.$customerId;
      $response = array();

      $images = glob("$path/*.{jpg,png,bmp,jpeg}", GLOB_BRACE);

      foreach($images as $image)
      {
        
        // $response[] = '<div class="orderprescrip" ><div class="bounditem"><a data-fancybox="images" href="' .  $Imagepath.DS.basename($image) . '"><img data-itemId="' . basename($image) . '" class="orderItem" src="' .  $Imagepath.DS.basename($image) . '" /></a><input type="checkbox" name="makeanorder[]"  class="presfileName"/></div></div>';

        $response[] = '<div class="orderprescrip" ><div class="bounditem"><a data-fancybox="images" href="' .  $Imagepath.DS.basename($image) . '"><img data-itemId="' . basename($image) . '" class="orderItem" src="' .  $Imagepath.DS.basename($image) . '" /></a></div></div>';
      }

      $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
      $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }



    public function makeanorderAction()
    {

      //$websiteId = Mage::app()->getStore()->getWebsiteId();
      //echo $websiteId;exit();
      //echo 'hhdadh';exit();
      $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
      $customerId = $currentCustomer->getId();
      $path = Mage::getBaseDir('media'). DS . 'uploadprescription'.DS.'uploadprescription'.DS.$customerId;
      $OrderPrescrip = $this->getRequest()->getPost();
      $orderFilePath =array();
      foreach ($OrderPrescrip['makeanorder'] as $key => $_rderPrescrip) {
        if($_rderPrescrip)
        {
          $orderFilePath[] = $path.DS.$_rderPrescrip;
        }
      }
      //echo '<pre>';print_r($orderFilePath);exit();

      $productObject = Mage::getModel('catalog/product');
      $customerObject = Mage::getModel('customer/customer')->load($customerId);
      $orderObjectexist = $productObject->getIdBySku($customerObject->getMobileNumber());
      try{

           

      if($orderObjectexist)
      {
                $product = Mage::getModel('catalog/product')->load($orderObjectexist)->setStoreId(1);
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
                $api = Mage::getModel('catalog/product_attribute_media_api');
                $images = $api->items($product->getId());

                if(isset($images))
                {
                      foreach($images as $img){
                        $MediaDir=Mage::getConfig()->getOptions()->getMediaDir();
                        $MediaCatalogDir=$MediaDir .DS . 'catalog' . DS . 'product';
                        $DirImagePath=str_replace("/",DS,$img['file']);
                        $io     = new Varien_Io_File();
                        $io->rm($MediaCatalogDir.$DirImagePath);
                        Mage::getModel('catalog/product_attribute_media_api')->remove($product->getId(),$img['file']);   
                      }   

                      //$product->save();
                }
                
               
                $newproduct = Mage::getModel('catalog/product')->load($orderObjectexist)->setStoreId(1);
                foreach($orderFilePath as $_orderFilePath)
                {
                      $mediaAttribute = array (
                          'image',
                          'thumbnail',
                          'small_image'                  
                      );
                      $newproduct->addImageToMediaGallery($_orderFilePath, $mediaAttribute, true, false);
                }
                $newproduct->save();

                
      }
      else
      {
            // echo 'Hii';exit();
                $productObject
                ->setWebsiteIds(array(1))
                ->setStoreId(1)//website ID the product is assigned to, as an array
                ->setAttributeSetId(4) //ID of a attribute set named 'default'
                ->setTypeId('simple') //product type
                ->setCreatedAt(strtotime('now')) //product creation time
                ->setName('Your Prescription')
                ->setSku($customerObject->getMobileNumber())
                ->setPrice(0)
                ->setStatus(1)
                ->setWeight(1)
                ->setTaxClassId(0)
                ->setIsPrescription(1)
                ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
                ->setStockData(
                  array(
                           'use_config_manage_stock' => 0,
                           'manage_stock'=>1, 
                           'is_in_stock' => 1, 
                           'qty' => 1000000
                       )
                  );
                foreach($orderFilePath as $_orderFilePath)
                {
                   $mediaAttribute = array (
                      'image',
                      'thumbnail',
                      'small_image'                  
                  );
                  $productObject->addImageToMediaGallery($_orderFilePath, $mediaAttribute, true, false);
                }
                $productObject->save();
      }

      $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
     

      

        try {
            
            Mage::app()->setCurrentStore(1);

           
            $customerID = $currentCustomer->getId();
            $orderCustomer = Mage::getModel('customer/customer')->load($customerID);
            $quote = Mage::getModel('sales/quote')->loadByCustomer($customerID);

            $quote->assignCustomer($orderCustomer);
            $product_id = Mage::getModel('catalog/product')->getIdBySku($orderCustomer->getMobileNumber());
            $Orderproduct = Mage::getModel('catalog/product')
                                 // set the current store ID
                                 ->setStoreId(Mage::app()->getStore()->getId())
                                 // load the product object
                                 ->load($product_id);

            // Add Product to Quote
            $quote->addProduct($Orderproduct,1);

            // Calculate the new Cart total and Save Quote
            $quote->collectTotals()->save();

            Mage::getSingleton("checkout/session")->addSuccess("Prescription is added to cart!!");

          $this->_redirect("checkout/cart/");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
       
      //Mage::getSingleton('core/session')->addSuccess(Mage::helper('customer')->__('Prescription is added to cart!!!'));
      
      

    }
    catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    }


    }

}