<?php


class Loritel_Uploadprescription_Block_Adminhtml_Uploadprescription extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_uploadprescription";
	$this->_blockGroup = "uploadprescription";
	$this->_headerText = Mage::helper("uploadprescription")->__("Uploadprescription Manager");
	$this->_addButtonLabel = Mage::helper("uploadprescription")->__("Add New Item");
	parent::__construct();
	
	}

}