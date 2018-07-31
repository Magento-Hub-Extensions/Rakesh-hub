<?php
class Loritel_Uploadprescription_Block_Adminhtml_Uploadprescription_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("uploadprescription_form", array("legend"=>Mage::helper("uploadprescription")->__("Prescription information")));

				
						$fieldset->addField("customer_name", "text", array(
						"label" => Mage::helper("uploadprescription")->__("Customer Name"),
						"name" => "customer_name",
						));
					
						$fieldset->addField("customer_mail", "text", array(
						"label" => Mage::helper("uploadprescription")->__("Mail Address"),
						"name" => "customer_mail",
						));
					
						$fieldset->addField("customer_phonenumber", "text", array(
						"label" => Mage::helper("uploadprescription")->__("PhoneNumber"),
						"name" => "customer_phonenumber",
						));
					
						$fieldset->addField("clinical_history", "textarea", array(
						"label" => Mage::helper("uploadprescription")->__("Clinical History"),
						"name" => "clinical_history",
						));
					
						$fieldset->addField("diagnosys", "textarea", array(
						"label" => Mage::helper("uploadprescription")->__("Diagnosys"),
						"name" => "diagnosys",
						));
									
						// $fieldset->addField('prescription_images', 'image', array(
						// 'label' => Mage::helper('uploadprescription')->__('Prescription Images'),
						// 'name' => 'prescription_images[]',
						// 'note' => '(*.jpg, *.png, *.gif)',
						// ));



						// $fieldset->addField('prescription_images', 'file', array(
				  //           'label'     => Mage::helper('uploadprescription')->__('Prescription Images upload'),
				  //           'value'  => 'prescription_images',
				  //           'disabled' => false,
				  //           'readonly' => true,                
				  //       )); 

				if (Mage::getSingleton("adminhtml/session")->getUploadprescriptionData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getUploadprescriptionData());
					Mage::getSingleton("adminhtml/session")->setUploadprescriptionData(null);
				} 
				elseif(Mage::registry("uploadprescription_data")) {
				    $form->setValues(Mage::registry("uploadprescription_data")->getData());
				}
				return parent::_prepareForm();
		}
}
