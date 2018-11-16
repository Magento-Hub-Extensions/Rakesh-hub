<?php
namespace Ueg\Crm\Model\ResourceModel;

class Dialer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ueg_dialer_customer_list', 'dialer_id');
    }
}
?>