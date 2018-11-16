<?php
namespace Ueg\Crm\Controller\Adminhtml\saleslog;

use Magento\Backend\App\Action;

/**
 * Class MassDelete
 */
class Ajaxsave extends \Magento\Backend\App\Action
{


    protected $CrmadminaclFactory;


    protected $SaleslogFactory;


    protected $date;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Ueg\Crm\Model\CrmadminaclFactory $CrmadminaclFactory,
        \Ueg\Crm\Model\SaleslogFactory $SaleslogFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->SaleslogFactory = $SaleslogFactory;
        $this->CrmadminaclFactory = $CrmadminaclFactory;
        $this->date = $date;
        parent::__construct($context);
    }
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
         $formVar = $this->getRequest()->getPost( 'form_var' );
        if ( isset( $formVar ) && ! empty( $formVar ) ) {
            parse_str( $formVar, $formParams );
            //echo '<pre>'; print_r( $formParams ); echo '</pre>';

            if(isset($formParams['sales'])) {
                foreach ( $formParams['sales'] as $param ) {
                    if (isset($param['delete']) && $param['delete'] == 1) {
                        $saleslogModel = $this->SaleslogFactory->create();
                        $saleslog = $saleslogModel->load($param['saleslog_id']);
                        $saleslog->delete();
                    }
                    if (isset($param['data']) && !empty($param['data'])) {
                        $data = $param['data'];

                        //echo '<pre>';print_r($data);exit();
                        $currentTime = $this->date->gmtDate();
                        $data['time_modified'] = $currentTime;

                        if(isset($data['order_date']))
                        {
                            $data['order_date'] = date('Y-m-d', strtotime($data['order_date']));
                        }
                        

                        if(isset($data['payment_received']))
                        {
                            $data['payment_received'] = date('Y-m-d', strtotime($data['payment_received']));
                        }
                        if(isset($data['qb_shipdate']))
                        {
                            $data['qb_shipdate'] = date('Y-m-d', strtotime($data['qb_shipdate']));
                        }

                        
                        
                        //echo '<pre>'; print_r( $param ); echo '</pre>';

                        $saleslogModel = $this->SaleslogFactory->create();
                        $saleslog = $saleslogModel->load($param['saleslog_id']);
                        $saleslog->setData( $data );
                        $saleslog->setId( $param['saleslog_id'] );
                        $saleslog->save();
                    }
                }
            }
        }

        echo 1;
    }
}