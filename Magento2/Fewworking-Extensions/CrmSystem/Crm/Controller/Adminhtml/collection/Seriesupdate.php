<?php

namespace Ueg\Crm\Controller\Adminhtml\collection;

use Magento\Backend\App\Action;

class Seriesupdate extends \Magento\Backend\App\Action {

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
        // print_r($params); exit;
        $customerId='';
        if(isset($params['customer_id']) && !empty($params['customer_id'])) {
            $customerId = $params['customer_id'];
            $resource        = $this->_resource;
            $writeConnection = $resource->getConnection();
            $tableName       = $resource->getTableName('ueg_coin_series_info');
            if ( isset( $params['series_info_id'] ) && ! empty( $params['series_info_id'] ) ) {
                $query = "UPDATE $tableName SET service_prefered = '" . $params['service_prefered'] . "', website = '" . $params['website'] . "', series_note = '" . $params['series_note'] . "', registry_name = '". $params['registry_name'] ."' WHERE series_info_id = " . $params['series_info_id'];
                $writeConnection->query( $query );
            } else {
                $query = "INSERT INTO $tableName (series_id, customer_id, service_prefered, website, series_note, registry_name) VALUES ('" . $params['series_id'] . "', '" . $params['customer_id'] . "', '" . $params['service_prefered'] . "', '" . $params['website'] . "', '" . $params['series_note'] . "', '". $params['registry_name'] ."')";
                $writeConnection->query( $query );
            }
        }

        // $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);

        if(isset($params['page']) && $params['page'] == "crm") {
            $this->_redirect('crm/crmcustomer/view', array( 'id' => $customerId ));
        } else {
            $this->_redirect('customer/index/edit', array( 'id' => $customerId ));
        }
        
        return;     
    }

}
