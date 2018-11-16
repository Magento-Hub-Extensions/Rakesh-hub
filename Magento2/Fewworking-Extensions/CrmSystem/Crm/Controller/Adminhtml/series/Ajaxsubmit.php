<?php

namespace Ueg\Crm\Controller\Adminhtml\series;

use Magento\Backend\App\Action\Context;


class Ajaxsubmit extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $_resource;

    protected $_coinFactory;

    protected $_seriesFactory;

    protected $date;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Ueg\Crm\Model\CoinFactory $CoinFactory,
        \Ueg\Crm\Model\SeriesFactory $SeriesFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
    ) {
        $this->_resource = $resource;
        $this->_coinFactory = $CoinFactory;
        $this->_seriesFactory = $SeriesFactory;
        $this->date = $date;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
            $formVar = $this->getRequest()->getPost( 'form_var' );
            //echo '<pre>';print_r($formVar);exit();
            if ( isset( $formVar ) && ! empty( $formVar ) ) {
                $currentTime = $this->date->formatDate();
                parse_str( $formVar, $formParams );

                $seriesId = "";
                if(isset($formParams['series_name']) && !empty($formParams['series_name'])) {
                    $seriesModel = $this->_seriesFactory->create();
                    $data = array(
                        'series_name' => trim($formParams['series_name']),
                        'status' => 1,
                        'created_at' => $currentTime,
                        'update_at' => $currentTime
                    );
                    $seriesModel->setData($data)->save();
                    $seriesId = $seriesModel->getId();
                }

                $collectionModel = $this->_coinFactory->create();
                //$param = array();
                if(isset($formParams['collection'])) {
                    foreach ( $formParams['collection'] as $param ) {
                        $param['coin_type'] = 2;
                        $param['series_id'] = $seriesId;
                        if(isset($formParams['user_defaults']) && $formParams['user_defaults'] == 1) {
                            $param['denom'] = trim( $formParams['denom'] );
                            $param['type']  = trim( $formParams['type'] );
                            $param['metal'] = trim( $formParams['metal'] );
                        }
                        $param['created_at']     = $currentTime;
                        $param['update_at']      = $currentTime;
                        $collectionModel->setData($param)->save();
                    }
                }
            }
        echo 1;
    }
}
?>