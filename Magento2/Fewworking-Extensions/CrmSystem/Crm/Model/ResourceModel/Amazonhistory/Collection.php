<?php

namespace Ueg\Crm\Model\ResourceModel\Amazonhistory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ueg\Crm\Model\Amazonhistory', 'Ueg\Crm\Model\ResourceModel\Amazonhistory');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>