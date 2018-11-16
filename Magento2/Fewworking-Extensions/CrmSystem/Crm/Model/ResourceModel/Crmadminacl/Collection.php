<?php

namespace Ueg\Crm\Model\ResourceModel\Crmadminacl;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ueg\Crm\Model\Crmadminacl', 'Ueg\Crm\Model\ResourceModel\Crmadminacl');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>