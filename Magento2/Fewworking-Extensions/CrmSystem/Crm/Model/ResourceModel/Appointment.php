<?php
namespace Ueg\Crm\Model\ResourceModel;

class Appointment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ueg_customer_appointment', 'appointment_id');
    }
}
?>