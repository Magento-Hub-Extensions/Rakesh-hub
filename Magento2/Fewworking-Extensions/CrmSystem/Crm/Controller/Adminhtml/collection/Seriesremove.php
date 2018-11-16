<?php

namespace Ueg\Crm\Controller\Adminhtml\collection;

use Magento\Backend\App\Action;

class Seriesremove extends \Magento\Backend\App\Action {

    protected $coinFactory;
    protected $crmHelper;
    protected $_resource;
    protected $resultRedirect;

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context, \Ueg\Crm\Model\CoinFactory $coinFactory, \Ueg\Crm\Helper\Data $crmHelper,\Magento\Framework\App\ResourceConnection $resource) {
        $this->coinFactory = $coinFactory;
        $this->crmHelper = $crmHelper;
        $this->_resource = $resource;
         $this->resultRedirect = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {

        $params = $this->getRequest()->getParams();
        if(isset($params['customer_id']) && !empty($params['customer_id'])) {
            $customerId = $params['customer_id'];
            $resource        = $this->_resource;
            $writeConnection = $resource->getConnection();
            $tableName1       = $resource->getTableName('ueg_coin_series_info');
            $tableName2       = $resource->getTableName('ueg_individual_coin');
            $tableName3       = $resource->getTableName('ueg_coin_series');

            if ( isset( $params['remove_series'] ) && ! empty( $params['remove_series'] ) ) {
                $query = "DELETE FROM $tableName1 WHERE customer_id = $customerId AND series_id = " . $params['remove_series'];
                $writeConnection->query( $query );

                $query = "DELETE FROM $tableName2 WHERE coin_type = 2 AND customer_id = $customerId AND series_id = " . $params['remove_series'];
                $writeConnection->query( $query );

                $countQuery = "SELECT COUNT(*) AS count FROM $tableName1 WHERE customer_id = $customerId AND series_id = " . $params['remove_series'];
                $count1 = $writeConnection->fetchOne($countQuery);
                $countQuery = "SELECT COUNT(*) AS count FROM $tableName2 WHERE coin_type = 2 AND customer_id = $customerId AND series_id = " . $params['remove_series'];
                $count2 = $writeConnection->fetchOne($countQuery);
                if(isset($count1) && $count1 > 0 && isset($count2) && $count2 > 0) {
                    $query = "DELETE FROM $tableName3 WHERE series_id = " . $params['remove_series'];
                    $writeConnection->query( $query );
                }
            }
        }
        // $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
        if(isset($params['page']) && $params['page'] == "crm") {
            $this->_redirect('crm/crmcustomer/view', array( 'id' => $customerId ) );
        } else {
            $this->_redirect('customer/index/edit', array( 'id' => $customerId ) );
        }
        return;
    }

}
