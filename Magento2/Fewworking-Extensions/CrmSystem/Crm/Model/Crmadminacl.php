<?php
namespace Ueg\Crm\Model;

class Crmadminacl extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ueg\Crm\Model\ResourceModel\Crmadminacl');
    }
}
?>