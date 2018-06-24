<?php
class Loritel_Uploadprescription_Model_Mysql4_Uploadprescription extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("uploadprescription/uploadprescription", "id");
    }
}