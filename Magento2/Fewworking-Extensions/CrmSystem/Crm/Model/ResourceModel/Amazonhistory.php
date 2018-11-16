<?php
namespace Ueg\Crm\Model\ResourceModel;

class Amazonhistory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('amazonlog_history', 'amazonhistory_id');
    }
}
?>