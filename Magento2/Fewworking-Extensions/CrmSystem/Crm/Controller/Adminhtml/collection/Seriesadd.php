<?php

namespace Ueg\Crm\Controller\Adminhtml\collection;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Magento\Backend\App\Action;

class Seriesadd extends \Magento\Backend\App\Action {

    protected $coinFactory;
    protected $crmHelper;
    protected $seriesFactory;
    protected $resultRedirect;

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context, \Ueg\Crm\Model\CoinFactory $coinFactory, \Ueg\Crm\Helper\Data $crmHelper, \Ueg\Crm\Model\SeriesFactory $SeriesFactory) {
        $this->coinFactory = $coinFactory;
        $this->crmHelper = $crmHelper;
        $this->seriesFactory = $SeriesFactory;
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
        if (isset($params['customer_id']) && !empty($params['customer_id'])) {
            $customerId = $params['customer_id'];

            if (isset($params['add_series']) && !empty($params['add_series'])) {
                $collection = $this->coinFactory->create()->getCollection()
                        ->addFieldToFilter('coin_type', 2)
                        ->addFieldToFilter('series_id', $params['add_series']);
                //echo "<pre>"; print_r($collection->getData()); echo "</pre>";
                foreach ($collection as $_coin) {
                    $data['customer_id'] = $customerId;
                    $data['pcgs'] = $_coin['pcgs'];
                    $data['year'] = $_coin['year'];
                    $data['mint'] = $_coin['mint'];
                    $data['variety'] = $_coin['variety'];
                    $data['desig'] = $_coin['desig'];
                    $data['denom'] = $_coin['denom'];
                    $data['type'] = $_coin['type'];
                    $data['metal'] = $_coin['metal'];
                    $data['created_at'] = $this->crmHelper->now();
                    $data['update_at'] = $this->crmHelper->now();
                    $data['coin_type'] = 2;
                    $data['series_id'] = $_coin['series_id'];

                    $this->coinFactory->create()->setData($data)->save();
                }
            }
        }
        // $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
        if (isset($params['page']) && $params['page'] == "crm") {
            $this->_redirect('crm/crmcustomer/view', array('id' => $customerId));
        } else {
            $this->_redirect('customer/index/edit', array('id' => $customerId));
        }

        return;
    }

}
