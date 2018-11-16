<?php
namespace Ueg\Crm\Block\Adminhtml\Asr;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Ueg\Crm\Model\asrFactory
     */
    protected $_asrFactory;

    /**
     * @var \Ueg\Crm\Model\Status
     */
    protected $_status;


    protected $_authSession;

    protected $_customerObject;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Ueg\Crm\Model\asrFactory $asrFactory
     * @param \Ueg\Crm\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Ueg\Crm\Model\AsrFactory $AsrFactory,
        \Ueg\Crm\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Customer\Model\CustomerFactory $customerObject,
        array $data = []
    ) {
        $this->_asrFactory = $AsrFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        $this->_authSession = $authSession;
        $this->_customerObject = $customerObject;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('asrpostGrid');
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
        $userId = $this->_authSession->getUser()->getUserId();
        
        $dialerCollection = $this->_asrFactory->create()->getCollection()
                            ->addFieldToFilter('user_id', $userId)
                            ->addFieldToFilter('status', 0);

        $customerIds = array();
        if(count($dialerCollection) > 0) {
            foreach ($dialerCollection as $_asr) {
                $customerIds[] = $_asr->getData('customer_id');
            }
        }

        $collection = $this->_customerObject->create()->getCollection()
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
        $this->addColumn('name', array(
            'header'    => __('Name'),
            'index'     => 'name',
            'filter'    => false,
            'sortable'  => false,
        ));

        $this->addColumn('email', array(
            'header'    => __('Email'),
            'index'     => 'email',
            'filter'    => false,
            'sortable'  => false,
        ));

        $this->addColumn('Telephone', array(
            'header'    => __('Telephone'),
            'index'     => 'billing_telephone',
            'filter'    => false,
            'sortable'  => false,
        ));

        $this->addColumn('billing_region', array(
            'header'    => __('State'),
            'index'     => 'billing_region',
            'filter'    => false,
            'sortable'  => false,
        ));

        $this->addColumn('billing_postcode', array(
            'header'    => __('ZIP'),
            'index'     => 'billing_postcode',
            'filter'    => false,
            'sortable'  => false,
        ));

        $this->addColumn(
            'action',
            array(
                'header' => __('Action'),
                'index' => 'asr_id',
                'filter' => false,
                'sortable' => false,
                'frame_callback' => array($this, 'callback_actioncolumn'),
            )
        );

	
        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	
  
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('crm/*/dashboard', ['_current' => true]);
    }

    /**
     * @param \Ueg\Crm\Model\asr|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return 'javascript:(void)';
		
    }


    public function callback_actioncolumn($value, $row, \Magento\Framework\DataObject $column, $isExport)
    {
        $html = '';

        $html .= '<div class="action" style="text-align: center;">';
        $html .= '<span><a class="grid-preview" href="'.$this->getUrl("crm/crmcustomer/view", array('id'=>$row->getData('entity_id'))).'?asr=true" target="_blank">View</a></span>';
        $html .= '</div>';

        return $html;
    }

	

}