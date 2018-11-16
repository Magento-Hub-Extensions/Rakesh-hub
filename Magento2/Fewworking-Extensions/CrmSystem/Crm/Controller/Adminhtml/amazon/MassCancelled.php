<?php
namespace Ueg\Crm\Controller\Adminhtml\amazon;

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
        $this->_storeManager = $context->getStoreManager();
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
         $saleslogIds = $this->getRequest()->getParam('product');
        
        //echo '<pre>'; print_r( $saleslogIds ); echo '</pre>';
        
        
        if(!is_array($saleslogIds)) {
            $this->messageManager->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($saleslogIds as $saleslogId) {
                    $saleslog = $this->SaleslogFactory->create()
                        ->load($saleslogId)
                        ->setShipping($saleslogId->getParam('store_bullion'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($saleslogIds))
                );
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}