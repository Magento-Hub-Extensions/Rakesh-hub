<?php
class Loritel_Uploadprescription_Block_Adminhtml_Uploadprescription_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("uploadprescription_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("uploadprescription")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("uploadprescription")->__("Item Information"),
				"title" => Mage::helper("uploadprescription")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("uploadprescription/adminhtml_uploadprescription_edit_tab_form")->toHtml(),
				));
				// $this->addTab("prescription_order", array(
				// "label" => Mage::helper("uploadprescription")->__("Prescription Order Image"),
				// "title" => Mage::helper("uploadprescription")->__("Prescription Order Image"),
				// "content" => $this->getLayout()->createBlock("uploadprescription/adminhtml_uploadprescription_edit_tab_orderimage")->toHtml(),
				// ));

				$this->addTab('prescription_order', array(
                'label'     => Mage::helper('uploadprescription')->__('Recent prescription image'),
                'url'       => $this->getUrl('*/*/orderimage', array('_current' => true)),
                'class'     => 'ajax',
            ));

				$this->addTab('prescription_orderproduct', array(
                'label'     => Mage::helper('uploadprescription')->__('Prescription Order Medicine'),
                'url'       => $this->getUrl('*/*/orderproduct', array('_current' => true)),
                'class'     => 'ajax',
            ));
				return parent::_beforeToHtml();
		}

}
