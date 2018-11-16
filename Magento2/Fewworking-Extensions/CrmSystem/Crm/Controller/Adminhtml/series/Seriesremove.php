<?php

namespace Ueg\Crm\Controller\Adminhtml\series;

use Magento\Backend\App\Action\Context;


class Seriesremove extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $_resource;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->_resource = $resource;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
            $params = $this->getRequest()->getParams();
            $writeConnection = $this->_resource->getConnection( 'core_write' );
            $tableName1       = $this->_resource->getTableName( 'ueg_coin_series_info' );
            $tableName2       = $this->_resource->getTableName( 'ueg_individual_coin' );
            $tableName3       = $this->_resource->getTableName( 'ueg_coin_series' );

        if ( isset( $params['remove_series'] ) && ! empty( $params['remove_series'] ) ) {
            $query = "DELETE FROM $tableName1 WHERE series_id = " . $params['remove_series'];
            $writeConnection->query( $query );

            $query = "DELETE FROM $tableName2 WHERE coin_type = 2 AND series_id = " . $params['remove_series'];
            $writeConnection->query( $query );

            $query = "DELETE FROM $tableName3 WHERE series_id = " . $params['remove_series'];
            $writeConnection->query( $query );
        }

        $this->_redirect('*/*/');
    }
}
?>