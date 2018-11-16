<?php
namespace Ueg\Crm\Model\ResourceModel;

class Series extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ueg_coin_series', 'series_id');
    }
}
?>