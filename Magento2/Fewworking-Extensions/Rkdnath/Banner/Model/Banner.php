<?php
namespace Rkdnath\Banner\Model;

class Banner extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rkdnath\Banner\Model\ResourceModel\Banner');
    }
}
?>