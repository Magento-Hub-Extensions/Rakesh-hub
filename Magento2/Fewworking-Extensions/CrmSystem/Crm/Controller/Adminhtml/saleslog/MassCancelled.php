<?php
namespace Ueg\Crm\Controller\Adminhtml\saleslog;

use Magento\Backend\App\Action;

/**
 * Class MassDelete
 */
class MassCancelled extends \Magento\Backend\App\Action
{



    protected $authSession;

    protected $customerCollection;

    protected $UserFactory;

    protected $CrmadminaclFactory;


    protected $SaleslogFactory;

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
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Customer\Model\CustomerFactory $customerObject,
        \Magento\User\Model\UserFactory $UserFactory,
        \Ueg\Crm\Model\CrmadminaclFactory $CrmadminaclFactory,
        \Ueg\Crm\Model\SaleslogFactory $SaleslogFactory
    ) {
        $this->authSession = $authSession;
        $this->customerCollection = $customerObject;
        $this->UserFactory = $UserFactory;
        $this->SaleslogFactory = $SaleslogFactory;
        $this->CrmadminaclFactory = $CrmadminaclFactory;
         parent::__construct($context);
    }
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
         $saleslogIds = $this->getRequest()->getParams();
        if(!is_array($saleslogIds['saleslog'])) {
            $this->messageManager->addError(__('Please select item(s)'));
        } else {
            try {
                foreach ($saleslogIds['saleslog'] as $saleslogId) {
                    $saleslog = $this->SaleslogFactory->create()->load($saleslogId);
                    $dollarValue = '0.00';
                    if(floatval($saleslog->getData('store_bullion')) > 0) {
                        $dollarValue = $saleslog->getData('store_bullion');
                    } elseif(floatval($saleslog->getData('ebay_numis')) > 0) {
                        $dollarValue = $saleslog->getData('ebay_numis');
                    } elseif(floatval($saleslog->getData('ebay_bullion')) > 0) {
                        $dollarValue = $saleslog->getData('ebay_bullion');
                    } elseif(floatval($saleslog->getData('store_numis')) > 0) {
                        $dollarValue = $saleslog->getData('store_numis');
                    }
                    $data = array(
                        'projected_shipdate' => 'cancelled',
                        'shipping' => $dollarValue,
                        'store_bullion' => '0.00',
                        'ebay_numis' => '0.00',
                        'ebay_bullion' => '0.00',
                        'store_numis' => '0.00',
                    );
                    $saleslog->setData($data)
                             ->setId($saleslogId)
                             ->save();
                }
                $this->messageManager->addSuccess(
                    __(
                        'Total of %1 record(s) were successfully set as cancelled', count($saleslogIds['saleslog'])
                    )
                );
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}