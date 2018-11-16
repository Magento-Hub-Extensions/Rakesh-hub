<?php

namespace Ueg\Crm\Controller\Adminhtml\coin;

use Magento\Backend\App\Action;

class Ajaxsubmit extends \Magento\Backend\App\Action {

    protected $coinFactory;
   protected $date;

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context, \Ueg\Crm\Model\CoinFactory $coinFactory, \Magento\Framework\Stdlib\DateTime\DateTime $date) {
        $this->coinFactory = $coinFactory;
        $this->date = $date;
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
                //echo '<pre>'; print_r( $formParams ); echo '</pre>';

                $coinModel = $this->coinFactory->create();
                if (isset($formParams['coin'])) {
                    foreach ($formParams['coin'] as $param) {
                        $currentTime = $this->date->gmtDate();
                        $param['coin_type'] = 1;
                        $param['customer_id'] = $customerId;
                        $param['date_requested'] = $currentTime;
                        $param['created_at'] = $currentTime;
                        $param['update_at'] = $currentTime;
                        //echo '<pre>'; print_r( $param ); echo '</pre>';

                        $coinModel->setData($param)->save();

                        if ($param['status'] == 'Upgrade') {
                            $param['status'] = 'Current';

                            $coinModel->setData($param)->save();
                        } elseif ($param['status'] == 'Current') {
                            $param['status'] = 'Upgrade';

                            $coinModel->setData($param)->save();
                        }
                    }
                }
            }
        }

        echo 1;
    }

}
