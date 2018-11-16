<?php
namespace Ueg\Crm\Model\ResourceModel;

class Repnotification extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ueg_rep_notification', 'notification_id');
    }
}
?>