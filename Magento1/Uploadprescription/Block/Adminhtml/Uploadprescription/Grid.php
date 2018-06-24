<?php

class Loritel_Uploadprescription_Block_Adminhtml_Uploadprescription_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("uploadprescriptionGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("uploadprescription/uploadprescription")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				// $this->addColumn("id", array(
				// "header" => Mage::helper("uploadprescription")->__("ID"),
				// "align" =>"right",
				// "width" => "50px",
			 //    "type" => "number",
				// "index" => "id",
				// ));
                
				$this->addColumn("customer_name", array(
				"header" => Mage::helper("uploadprescription")->__("Customer Name"),
				"index" => "customer_name",
				));
				$this->addColumn("customer_mail", array(
				"header" => Mage::helper("uploadprescription")->__("Mail Address"),
				"index" => "customer_mail",
				));
				$this->addColumn("customer_phonenumber", array(
				"header" => Mage::helper("uploadprescription")->__("PhoneNumber"),
				"index" => "customer_phonenumber",
				));
				$this->addColumn("clinical_history", array(
				"header" => Mage::helper("uploadprescription")->__("Clinical History"),
				"index" => "clinical_history",
				));
				$this->addColumn("diagnosys", array(
				"header" => Mage::helper("uploadprescription")->__("Diagnosys"),
				"index" => "diagnosys",
				));
				$this->addColumn("prescription_images", array(
				"header" => Mage::helper("uploadprescription")->__("Prescription Images"),
				"index" => "prescription_images",
				'renderer'  => 'Loritel_Uploadprescription_Block_Adminhtml_Uploadprescription_UploadimageRender',
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_uploadprescription', array(
					 'label'=> Mage::helper('uploadprescription')->__('Remove Uploadprescription'),
					 'url'  => $this->getUrl('*/adminhtml_prescription/massRemove'),
					 'confirm' => Mage::helper('uploadprescription')->__('Are you sure?')
				));
			return $this;
		}
			

}