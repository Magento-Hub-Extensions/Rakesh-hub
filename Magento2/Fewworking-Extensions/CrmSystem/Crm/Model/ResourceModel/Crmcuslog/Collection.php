<?php

namespace Ueg\Crm\Model\ResourceModel\Crmcuslog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ueg\Crm\Model\Crmcuslog', 'Ueg\Crm\Model\ResourceModel\Crmcuslog');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>