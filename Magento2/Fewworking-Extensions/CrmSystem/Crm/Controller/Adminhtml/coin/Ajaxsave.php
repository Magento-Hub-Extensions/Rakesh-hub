<?php

namespace Ueg\Crm\Controller\Adminhtml\coin;

use Magento\Backend\App\Action;

class Ajaxsave extends \Magento\Backend\App\Action {

    protected $coinFactory;
    protected $crmHelper;
    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context, \Ueg\Crm\Model\CoinFactory $coinFactory, \Ueg\Crm\Helper\Data $crmHelper) {
        $this->coinFactory = $coinFactory;
        $this->crmHelper = $crmHelper;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $customerId = $this->getRequest()->getPost('customer_id');
        if (isset($customerId) && !empty($customerId)) {
            $formVar = $this->getRequest()->getPost('form_var');
            if (isset($formVar) && !empty($formVar)) {
                parse_str($formVar, $formParams);
//                echo '<pre>'; print_r( $formParams ); echo '</pre>'; exit;

                if (isset($formParams['coin'])) {
                    foreach ($formParams['coin'] as $param) {
                        
                       // print_r($param);                        continue;
                        
                        if (isset($param['delete']) && $param['delete'] == 1) {
                            $coinModel = $this->coinFactory->create();
                            $coin = $coinModel->load($param['coin_id']);
                            $coin->delete();
                            
                        } elseif (isset($param['update']) && $param['update'] == 1) {
                           $currentTime = $this->crmHelper->now();
                            //$param['customer_id']    = $customerId;
//                            $param['date_requested'] = str_replace('/', '-', $param['date_requested']); 
                            $date_arr = explode("/", $param['date_requested']);
                            $param['date_requested'] = $date_arr[2]."-".$date_arr[0]."-".$date_arr[1];
                            // $param['date_requested'] = date('Y-m-d', strtotime($param['date_requested']));

                            $param['date_requested'] = $this->crmHelper->getFormattedDate($param['date_requested']);

                            //$param['created_at']     = $currentTime;
                            $param['update_at'] = $currentTime;
                            //echo '<pre>'; print_r( $param ); echo '</pre>';

                            $coinModel = $this->coinFactory->create();
                            $coin = $coinModel->load($param['coin_id']);
                            $coin->setData($param);
                            $coin->setId($param['coin_id']);
                            $saved_id =  $coin->save()->getId();
                            
                        }
                    }
                }
            }
        }

        echo 1;
    }

}
