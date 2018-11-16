<?php
namespace Ueg\Crm\Model\ResourceModel;

class Crminvoice extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ueg_crm_invoice', 'invoice_id');
    }
}
?>