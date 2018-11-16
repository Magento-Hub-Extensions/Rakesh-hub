<?php
namespace Ueg\Crm\Block\Adminhtml\Dialer;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Ueg\Crm\Model\dialerFactory
     */
    protected $_dialerFactory;

    /**
     * @var \Ueg\Crm\Model\Status
     */
    

    protected $authSession;

    protected $customerCollection;

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
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Ueg\Crm\Model\DialerFactory $DialerFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Customer\Model\CustomerFactory $customerObject,
        array $data = []
    ) {
        $this->_dialerFactory = $DialerFactory;
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->customerCollection = $customerObject;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('dialerGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {

        
        $userId = $this->authSession->getUser()->getUserId();
        
        $dialerCollection = $this->_dialerFactory->create()->getCollection()
                            ->addFieldToFilter('user_id', $userId)
                            ->addFieldToFilter('status', 0);

        $customerIds = array();
        if(count($dialerCollection) > 0) {
            foreach ($dialerCollection as $_dialer) {
                $customerIds[] = $_dialer->getData('customer_id');
            }
        }

        $collection = $this->customerCollection->create()->getCollection()
                          ->addNameToSelect()
                          ->addAttributeToSelect('email')
                          ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
                          ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
                          ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
                          ->addFieldToFilter('entity_id', array('in' => $customerIds));

        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        
				$this->addColumn(
					'name',
					[
						'header' => __('Name'),
						'index' => 'name',
					]
				);
				
				$this->addColumn(
					'email',
					[
						'header' => __('Email'),
						'index' => 'email',
					]
				);
				
				$this->addColumn(
					'billing_telephone',
					[
						'header' => __('Telephone'),
						'index' => 'billing_telephone',
					]
				);
				
				$this->addColumn(
					'billing_region',
					[
						'header' => __('State'),
						'index' => 'billing_region',
					]
				);
				
				$this->addColumn(
					'billing_postcode',
					[
						'header' => __('ZIP'),
						'index' => 'billing_postcode',
					]
				);
				
				$this->addColumn(
					'action',
					[
						'header' => __('Action'),
						'index' => 'asr_id',
                        'filter' => false,
                        'sortable' => false,
                        'frame_callback' => array($this, 'callback_actioncolumn'),
					]
				);
				
		   //$this->addExportType($this->getUrl('crm/*/exportCsv', ['_current' => true]),__('CSV'));
		   //$this->addExportType($this->getUrl('crm/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        parent::_prepareColumns();
        return $this;
    }

	

		

     /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('crm/*/dashboard', ['_current' => true]);
    }

    /**
     * @param \Ueg\Crm\Model\dialer|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return 'javascript:void(0)';
		
    }

    public function callback_actioncolumn($value, $row, \Magento\Framework\DataObject $column, $isExport)
    {
        $html = '';

        $html .= '<div class="action" style="text-align: center;">';
        $html .= '<span><a class="grid-preview" href="'.$this->getUrl("crm/crmcustomer/view", array('id'=>$row->getData('entity_id'))).'?dialer=true" target="_blank">View</a></span>';
        $html .= '</div>';

        return $html;
    }

	

}