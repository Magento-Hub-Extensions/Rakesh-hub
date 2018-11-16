<?php
namespace Ueg\Crm\Model\ResourceModel;

class Amazon extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('amazonlog', 'amazon_id');
    }
}
?>