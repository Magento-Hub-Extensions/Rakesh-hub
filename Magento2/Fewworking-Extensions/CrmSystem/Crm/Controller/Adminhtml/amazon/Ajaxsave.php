<?php
namespace Ueg\Crm\Controller\Adminhtml\amazon;

use Magento\Backend\App\Action;

/**
 * Class MassDelete
 */
class Ajaxsave extends \Magento\Backend\App\Action
{


    protected $CrmadminaclFactory;


    protected $SaleslogFactory;


    protected $date;

    protected $AmazonFactory;

    protected $AmazonhistoryFactory;

    protected $authSession;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Ueg\Crm\Model\dialerFactory $dialerFactory
     * @param \Ueg\Crm\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\User\Model\UserFactory $UserFactory,
        \Ueg\Crm\Model\CrmadminaclFactory $CrmadminaclFactory,
        \Ueg\Crm\Model\SaleslogFactory $SaleslogFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Ueg\Crm\Model\AmazonFactory $AmazonFactory,
        \Ueg\Crm\Model\AmazonhistoryFactory $AmazonhistoryFactory,
        \Magento\Backend\Model\Auth\Session $authSession
    ) {
        $this->UserFactory = $UserFactory;
        $this->SaleslogFactory = $SaleslogFactory;
        $this->CrmadminaclFactory = $CrmadminaclFactory;
        $this->date = $date;
        $this->AmazonFactory = $AmazonFactory;
        $this->AmazonhistoryFactory = $AmazonhistoryFactory;
        $this->authSession = $authSession;
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
            

            if(isset($formParams['amazon'])) {
                foreach ( $formParams['amazon'] as $param ) {
                    if (isset($param['data']) && !empty($param['data'])) {
                        $data = $param['data'];
                        $currentTime = $this->date->gmtDate();
                        $data['update_at'] = $currentTime;
                        $amazonlogModel = $this->AmazonFactory->create();
                        $amazonlog = $amazonlogModel->load($param['amazon_id']);

                        $historyData = array(
                            'amazon_id' => $amazonlog->getData('amazon_id'),
                            'cost' => $amazonlog->getData('cost'),
                            'modified_user_name' => $this->authSession->getUser()->getUsername(),
                            'modified_at' => $this->date->gmtDate()
                        );
                        $this->AmazonhistoryFactory->create()->setData($historyData)->save();

                        $data['net_profit_dollar'] = (float)($amazonlog->getData('price') - $amazonlog->getData('amazon_fee') - $data['cost']);
                        $data['net_profit_percentage'] = (float)(($data['net_profit_dollar']/$amazonlog->getData('price'))*100);
                        $amazonlog->setData( $data );
                        $amazonlog->setId( $param['amazon_id'] );
                        $amazonlog->save();
                    }
                }
            }
        }

        echo 1;
    }
}