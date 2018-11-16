<?php

namespace Ueg\Crm\Controller\Adminhtml\collection;

use Magento\Backend\App\Action;

class Ajaxsubmit extends \Magento\Backend\App\Action {

    protected $coinFactory;
    protected $crmHelper;
    protected $seriesFactory;

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context, \Ueg\Crm\Model\CoinFactory $coinFactory, \Ueg\Crm\Helper\Data $crmHelper,\Ueg\Crm\Model\SeriesFactory $SeriesFactory) {
        $this->coinFactory = $coinFactory;
        $this->crmHelper = $crmHelper;
        $this->seriesFactory = $SeriesFactory;

        parent::__construct($context);
    }
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {

        $customerId = $this->getRequest()->getPost('customer_id');
        if(isset($customerId) && !empty($customerId)) {
            $formVar = $this->getRequest()->getPost( 'form_var' );
            if ( isset( $formVar ) && ! empty( $formVar ) ) {
                $currentTime = $this->crmHelper->now();
                parse_str( $formVar, $formParams );
                //echo '<pre>'; print_r( $formParams ); echo '</pre>';

                $seriesId = "";
                if(isset($formParams['series_name']) && !empty($formParams['series_name'])) {
                    $seriesModel = $this->seriesFactory->create();
                    $data = array(
                        'series_name' => trim($formParams['series_name']),
                        'status' => 1,
                        'created_at' => $currentTime,
                        'update_at' => $currentTime
                    );
                    $seriesModel->setData($data)->save();
                    $seriesId = $seriesModel->getId();
                }

                $collectionModel = $this->coinFactory->create();
                if(isset($formParams['collection'])) {
                    foreach ( $formParams['collection'] as $param ) {
                        $param['coin_type'] = 2;
                        $param['series_id'] = $seriesId;
                        $param['customer_id'] = $customerId;
                        if(isset($formParams['user_defaults']) && $formParams['user_defaults'] == 1) {
                            $param['denom'] = trim( $formParams['denom'] );
                            $param['type']  = trim( $formParams['type'] );
                            $param['metal'] = trim( $formParams['metal'] );
                        }

                        //$param['date_requested'] = $currentTime;
                        $param['created_at']     = $currentTime;
                        $param['update_at']      = $currentTime;
                        //echo '<pre>'; print_r( $param ); echo '</pre>';

                        $collectionModel->setData($param)->save();
                    }
                }
            }
        }

        echo 1;
    }

}
