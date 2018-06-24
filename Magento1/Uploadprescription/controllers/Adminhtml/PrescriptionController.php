<?php
require_once 'OrderGenerator.php';
class Loritel_Uploadprescription_Adminhtml_PrescriptionController extends Mage_Adminhtml_Controller_Action
{

	protected $shippingAddress;
	protected $billingAddress;
		protected function _isAllowed()
		{
		    return Mage::getSingleton('admin/session')->isAllowed('uploadprescription/uploadprescription');
			//return true;
		}

		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("uploadprescription/uploadprescription")->_addBreadcrumb(Mage::helper("adminhtml")->__("Uploadprescription  Manager"),Mage::helper("adminhtml")->__("Uploadprescription Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Uploadprescription"));
			    $this->_title($this->__("Manager Uploadprescription"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Uploadprescription"));
				$this->_title($this->__("Uploadprescription"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("uploadprescription/uploadprescription")->load($id);
				if ($model->getId()) {
					Mage::register("uploadprescription_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("uploadprescription/uploadprescription");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Uploadprescription Manager"), Mage::helper("adminhtml")->__("Uploadprescription Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Uploadprescription Description"), Mage::helper("adminhtml")->__("Uploadprescription Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("uploadprescription/adminhtml_uploadprescription_edit"))->_addLeft($this->getLayout()->createBlock("uploadprescription/adminhtml_uploadprescription_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("uploadprescription")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Uploadprescription"));
		$this->_title($this->__("Uploadprescription"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("uploadprescription/uploadprescription")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("uploadprescription_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("uploadprescription/uploadprescription");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Uploadprescription Manager"), Mage::helper("adminhtml")->__("Uploadprescription Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Uploadprescription Description"), Mage::helper("adminhtml")->__("Uploadprescription Description"));


		$this->_addContent($this->getLayout()->createBlock("uploadprescription/adminhtml_uploadprescription_edit"))->_addLeft($this->getLayout()->createBlock("uploadprescription/adminhtml_uploadprescription_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						$model = Mage::getModel("uploadprescription/uploadprescription")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Uploadprescription was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setUploadprescriptionData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setUploadprescriptionData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("uploadprescription/uploadprescription");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("uploadprescription/uploadprescription");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'uploadprescription.csv';
			$grid       = $this->getLayout()->createBlock('uploadprescription/adminhtml_uploadprescription_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'uploadprescription.xml';
			$grid       = $this->getLayout()->createBlock('uploadprescription/adminhtml_uploadprescription_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
		public function orderproductAction () {
			$this->loadLayout();
			$this->getLayout()->getBlock('edit.tab.orderproductgrid');        
			$this->renderLayout();   

		}
		public function orderimageAction () {
			$this->loadLayout();
			$this->getLayout()->getBlock('edit.tab.orderimage');        
			$this->renderLayout();   

		}

public function makeanOrderAction()
{
	$paramValue = $this->getRequest()->getParams();
	$CurrentPrescriptionId = $this->getRequest()->getParam('pres');
	$ProductIds = $this->getRequest()->getParam('ids');
	$OrderImage = $this->getRequest()->getParam('order-image');
	//echo '<pre>';print_r($ProductIds);exit();
	$model = Mage::getModel("uploadprescription/uploadprescription")->load($CurrentPrescriptionId);
	$customerMail = $model->getCustomerMail();

	$customer = Mage::getModel("customer/customer"); 
	$customer->setWebsiteId(1); 
	$customer->loadByEmail($customerMail); 

	$customerId = $customer->getId();
	$path = Mage::getBaseDir('media'). DS . 'uploadprescription'.DS.'uploadprescription'.DS.$customerId;

	$_orderFilePath = $path.DS.$OrderImage;

	$productObject =Mage::getModel('catalog/product');
	$orderObjectexist = $productObject->getIdBySku($customer->getMobileNumber());
	try{

	  if($orderObjectexist)
	  {
	  	//echo 'Hii';exit();
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

	              $mediaAttribute = array (
	                  'image',
	                  'thumbnail',
	                  'small_image'                  
	              );
	            $newproduct = Mage::getModel('catalog/product')->load($orderObjectexist)->setStoreId(1);
	            $newproduct->addImageToMediaGallery($_orderFilePath, $mediaAttribute, true, false);
	           
	            $newproduct->save();

	            
	  }
	  else
	  {
	            $productObject
	            ->setWebsiteIds(array(1))
	            ->setStoreId(1)//website ID the product is assigned to, as an array
	            ->setAttributeSetId(4) //ID of a attribute set named 'default'
	            ->setTypeId('simple') //product type
	            ->setCreatedAt(strtotime('now')) //product creation time
	            ->setName('Your Prescription')
	            ->setSku($customer->getMobileNumber())
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
	         
				$mediaAttribute = array (
				  'image',
				  'thumbnail',
				  'small_image'                  
				);
				$productObject->addImageToMediaGallery($_orderFilePath, $mediaAttribute, true, false);

				$productObject->save();
		  }



		   		$orderGenerator = new OrderGenerator();
		   		$orderGenerator->setShippingMethod('freeshipping');
				$orderGenerator->setCustomer($customer->getId());
				$product_id = Mage::getModel('catalog/product')->getIdBySku($customer->getMobileNumber());

				
				$productData = array();
				$productData[0] = array(
				                'product' => $product_id,
				                'qty' => 1
				       			 );

				for($i=0;$i<sizeof($ProductIds);$i++)
				{
					$productData[$i+1]= array(
						                'product' => $ProductIds[$i],
						                'qty' => 1
						       			 );
				}

		        $orderGenerator->createOrder($productData);

		      Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Order Is successfully Made!!"));
		      $this->_redirect("*/*/edit", array("id" => $CurrentPrescriptionId));


		}


		catch(Exception $e)
		{
			echo $e->getMessage();
		}

	}


}
