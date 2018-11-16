<?php
namespace Ueg\Crm\Model\ResourceModel;

class Coin extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ueg_individual_coin', 'coin_id');
    }
}
?>