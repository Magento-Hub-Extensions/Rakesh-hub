<?php

namespace Ueg\Crm\Model\ResourceModel\Amazon;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ueg\Crm\Model\Amazon', 'Ueg\Crm\Model\ResourceModel\Amazon');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>