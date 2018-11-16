<?php
namespace Ueg\Crm\Block\Adminhtml\Hotclient;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    

    /**
     * @var \Ueg\Crm\Model\Status
     */
    protected $_template = 'hotclient/grid/extended.phtml';

    protected $authSession;

    protected $customerCollection;


    protected $_groupFactory;

    protected $_systemStore;

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
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Customer\Model\CustomerFactory $customerObject,
        \Magento\Customer\Model\GroupFactory $groupFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->customerCollection = $customerObject;
        $this->_groupFactory = $groupFactory;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('hotclientGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('hotclientpost_filter');
    }


    public function getHeaderText()
    {
        return "Hot Clients";
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {

        
        $collection = $this->customerCollection->create()->getCollection()
                          ->addAttributeToFilter('hot_client', 1)
                          ->addNameToSelect()
                          ->addAttributeToSelect('email')
                          ->addAttributeToSelect('created_at')
                          ->addAttributeToSelect('group_id')
                          ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
                          ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
                          ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
                          ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
                          ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left');

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
        
		$this->addColumn('entity_id', array(
            'header'    => __('ID'),
            'width'     => '50px',
            'index'     => 'entity_id',
            'type'  => 'number',
        ));
       
        $this->addColumn('name', array(
            'header'    => __('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('email', array(
            'header'    => __('Email'),
            'width'     => '150',
            'index'     => 'email'
        ));

        $groups = $this->_groupFactory->create()->getCollection()
                      ->addFieldToFilter('customer_group_id', array('gt'=> 0))
                      ->load()
                      ->toOptionHash();

        $this->addColumn('group', array(
            'header'    =>  __('Group'),
            'width'     =>  '100',
            'index'     =>  'group_id',
            'type'      =>  'options',
            'options'   =>  $groups,
        ));

        $this->addColumn('Telephone', array(
            'header'    => __('Telephone'),
            'width'     => '100',
            'index'     => 'billing_telephone'
        ));

        $this->addColumn('billing_postcode', array(
            'header'    => __('ZIP'),
            'width'     => '90',
            'index'     => 'billing_postcode',
        ));

        $this->addColumn('billing_country_id', array(
            'header'    => __('Country'),
            'width'     => '100',
            'type'      => 'country',
            'index'     => 'billing_country_id',
        ));

        $this->addColumn('billing_region', array(
            'header'    => __('State/Province'),
            'width'     => '100',
            'index'     => 'billing_region',
        ));

        $this->addColumn('customer_since', array(
            'header'    => __('Customer Since'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'created_at',
            'gmtoffset' => true
        ));

        if ($this->_storeManager->isSingleStoreMode()) {
            $this->addColumn('website_id', array(
                'header'    => __('Website'),
                'align'     => 'center',
                'width'     => '80px',
                'type'      => 'options',
                'options'   => $this->_systemStore->getWebsiteOptionHash(true),
                'index'     => 'website_id',
            ));
        }

        $this->addColumn('action',
            array(
                'header'    =>  __('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => __('Edit'),
                        'url'       => array('base'=> 'customer/index/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            ));

        $this->addExportType('crm/*/exportCsv', __('CSV'));
        $this->addExportType('crm/*/exportExcel', __('XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        parent::_prepareColumns();
        return $this;
    }

	

    public function getGridUrl()
    {
        return $this->getUrl('crm/hotclient/grid', array('_current'=> true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('customer/index/edit', array('id'=>$row->getId()));
    }

	

}