<?php
	
class Loritel_Uploadprescription_Block_Adminhtml_Uploadprescription_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "uploadprescription";
				$this->_controller = "adminhtml_uploadprescription";
				$this->_updateButton("save", "label", Mage::helper("uploadprescription")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("uploadprescription")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("uploadprescription")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("uploadprescription_data") && $uploadId = Mage::registry("uploadprescription_data")->getId() ){
					 $uploadPrescription = Mage::getModel('uploadprescription/uploadprescription')->load($uploadId);
					 $customerName = $uploadPrescription->getCustomerName();
				    return Mage::helper("uploadprescription")->__("Edit Item '%s'", $this->htmlEscape($customerName));

				} 
				else{

				     return Mage::helper("uploadprescription")->__("Add Item");

				}
		}
}