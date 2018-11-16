<?php
namespace Ueg\Crm\Block\Adminhtml\Master;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    

    /**
     * @var \Ueg\Crm\Model\Status
     */
    protected $_template = 'master/grid/extended.phtml';

    protected $authSession;

    protected $customerCollection;


    protected $_groupFactory;

    protected $_systemStore;

    protected $_coin;

    protected $_eavAttribute;

    protected $_resource;

    protected $_coinOptions;

    protected $_attrAssign;

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
        \Ueg\Crm\Model\CoinFactory $CoinFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $attribute,
        \Magento\Framework\App\ResourceConnection $resource,
        \Ueg\Crm\Model\Coinoptions $coinOptions,
        \Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerAssignedToOptions $AssignOption,
        array $data = []
    ) {
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->customerCollection = $customerObject;
        $this->_groupFactory = $groupFactory;
        $this->_systemStore = $systemStore;
        $this->_coin = $CoinFactory;
        $this->_eavAttribute = $attribute;
        $this->_resource = $resource;
        $this->_coinOptions = $coinOptions;
        $this->_attrAssign = $AssignOption;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('masterGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('masterpost_filter');
    }


    public function getHeaderText()
    {
        return "Master Client";
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {

        
        $collection = $this->_coin->create()->getCollection();
        $collection->getSelect()->joinLeft(
            array('se'=>'ueg_coin_series'),
            'main_table.series_id = se.series_id',
            array('series_name')
        );
        $collection->getSelect()->joinLeft(
            array('sei'=>'ueg_coin_series_info'),
            'main_table.series_id = sei.series_id',
            array('registry_name', 'series_note')
        );
        $collection->getSelect()->join(
            array('ce'=>'customer_entity'),
            'main_table.customer_id = ce.entity_id',
            array('email')
        );
        $collection->getSelect()->joinLeft(
            array('cev_fn'=>'customer_entity_varchar'),
            'main_table.customer_id = cev_fn.entity_id AND cev_fn.attribute_id = '. $this->_eavAttribute->getIdByCode('customer', 'firstname'),
            array('firstname' => 'value')
        );
        $collection->getSelect()->joinLeft(
            array('cev_ln'=>'customer_entity_varchar'),
            'main_table.customer_id = cev_ln.entity_id AND cev_ln.attribute_id = '. $this->_eavAttribute->getIdByCode('customer', 'lastname'),
            array('lastname' => 'value')
        );

        $collection->getSelect()->joinLeft(
            array('cei_hc'=>'customer_entity_int'),
            'main_table.customer_id = cei_hc.entity_id AND cei_hc.attribute_id = '. $this->_eavAttribute->getIdByCode('customer', 'hot_client'),
            array('hot_client' => 'value')
        );
        $collection->getSelect()->joinLeft(
            array('cev_cc'=>'customer_entity_varchar'),
            'main_table.customer_id = cev_cc.entity_id AND cev_cc.attribute_id = '. $this->_eavAttribute->getIdByCode('customer', 'client_code'),
            array('client_code' => 'value')
        );
        $collection->getSelect()->joinLeft(
            array('cet_at'=>'customer_entity_text'),
            'main_table.customer_id = cet_at.entity_id AND cet_at.attribute_id = '. $this->_eavAttribute->getIdByCode('customer', 'assigned_to'),
            array('assigned_to' => 'value')
        );
        $collection->getSelect()->joinLeft(
            array('ced_lc'=>'customer_entity_datetime'),
            'main_table.customer_id = ced_lc.entity_id AND ced_lc.attribute_id = '. $this->_eavAttribute->getIdByCode('customer', 'last_contacted'),
            array('last_contacted' => 'value')
        );
        $collection->getSelect()->joinLeft(
            array('cei_db'=>'customer_entity_int'),
            'main_table.customer_id = cei_db.entity_id AND cei_db.attribute_id = '. $this->_eavAttribute->getIdByCode('customer', 'default_billing'),
            array('default_billing' => 'value')
        );
        $collection->getSelect()->joinLeft(
            array('caev_t1'=>'customer_address_entity_varchar'),
            'caev_t1.entity_id = cei_db.value AND caev_t1.attribute_id = '. $this->_eavAttribute->getIdByCode('customer_address', 'telephone'),
            array('telephone1' => 'value')
        );
        $collection->getSelect()->joinLeft(
            array('cae'=>'customer_address_entity'),
            'main_table.customer_id = cae.parent_id AND cae.entity_id != cei_db.value',
            array('seconday_address' => 'entity_id')
        );
        $collection->getSelect()->joinLeft(
            array('caev_t2'=>'customer_address_entity_varchar'),
            'caev_t2.entity_id = cae.entity_id AND caev_t2.attribute_id = '. $this->_eavAttribute->getIdByCode('customer_address', 'telephone'),
            array('telephone2' => 'value')
        );

        // add 2 new fields as sub queries
        $sql ='SELECT MAX(o.created_at)'
              . ' FROM ' . $this->_resource->getTableName('sales_order') . ' AS o'
              . ' WHERE o.customer_id = main_table.customer_id ';

        $expr = new \Zend_Db_Expr('(' . $sql . ')');

        $collection->getSelect()->from(null, array('last_order_date'=>$expr));

        $series = $this->getRequest()->getParam('series');
        if(isset($series) && !empty($series)) {
            $collection->addFieldToFilter('series_id', $series);
        }

        //echo "<pre>"; print_r($collection->getData()); echo "</pre>";


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
		$coinOptions = $this->_coinOptions;

        $this->addColumn('coin_id', array(
            'header'    => __('ID'),
            'width'     => '50px',
            'index'     => 'coin_id',
            'type'  => 'number',
            'column_css_class'=>'no-display',
            'header_css_class'=>'no-display',
        ));

        $assignedOption = $this->_attrAssign->getOptionArray();
        array_shift($assignedOption);
        $assignedOptions = array();
        foreach ($assignedOption as $key => $_assignedOption) {
            $assignedOptions[$key] = $_assignedOption;
        }
        $this->addColumn(
            'assigned_to',
            array(
                'header' => __('Rep (Assigned to)'),
                'index' => 'assigned_to',
                //'type'      =>  'options',
                //'options'   =>  $assignedOptions,
                'filter' => false,
                'sortable' => false,
                'frame_callback' => array($this, 'callback_assignedto'),
            )
        );

        $this->addColumn(
            'series_name',
            array(
                'header' => __('Series'),
                'index' => 'series_name',
            )
        );

        $this->addColumn(
            'client_code',
            array(
                'header' => __('Client Code'),
                'index' => 'client_code',
            )
        );

        $this->addColumn(
            'last_contacted',
            array(
                'header' => __('Last Contact'),
                'index' => 'last_contacted',
                'type'      => 'date',
                //'gmtoffset' => true,
            )
        );

        $this->addColumn(
            'last_order_date',
            array(
                'header' => __('Last Purchase'),
                'index' => 'last_order_date',
                'type'      => 'date',
                //'gmtoffset' => true,
            )
        );

        $this->addColumn(
            'firstname',
            array(
                'header' => __('Firstname'),
                'index' => 'firstname',
            )
        );

        $this->addColumn(
            'lastname',
            array(
                'header' => __('Lastname'),
                'index' => 'lastname',
            )
        );

        $service = $coinOptions->getServiceOptions();
        $serviceOptions = array();
        foreach ($service as $_service) {
            $serviceOptions[$_service] = $_service;
        }
        $this->addColumn(
            'service',
            array(
                'header' => __('Service'),
                'index' => 'service',
                'type'      =>  'options',
                'options'   =>  $serviceOptions,
            )
        );

        $this->addColumn(
            'registry_name',
            array(
                'header' => __('Registry Name'),
                'index' => 'registry_name',
            )
        );

        $this->addColumn(
            'telephone1',
            array(
                'header' => __('Primary #'),
                'index' => 'telephone1',
            )
        );

        $this->addColumn(
            'telephone2',
            array(
                'header' => __('Secondary #'),
                'index' => 'telephone2',
            )
        );

        $this->addColumn(
            'email',
            array(
                'header' => __('Email'),
                'index' => 'email',
            )
        );

        $this->addColumn(
            'note',
            array(
                'header' => __('Notes'),
                'index' => 'note',
            )
        );

        $this->addColumn(
            'hot_client',
            array(
                'header' => __('Hot Client'),
                'index' => 'hot_client',
                'type'      =>  'options',
                'options'   =>  array(0 => 'No', 1 => 'Yes'),
            )
        );

        $this->addColumn(
            'coin_type',
            array(
                'header' => __('Is Collection'),
                'index' => 'coin_type',
                'type'      =>  'options',
                'options'   =>  array(1 => 'No', 2 => 'Yes'),
            )
        );

        $this->addColumn(
            'action',
            array(
                'header' => __('Action'),
                'index' => 'customer_id',
                'filter' => false,
                'sortable' => false,
                'frame_callback' => array($this, 'callback_actioncolumn'),
            )
        );


        //$this->addExportType('crm/*/exportCsv', __('CSV'));
       // $this->addExportType('crm/*/exportExcel', __('XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
        

        parent::_prepareColumns();
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('crm/master/grid', array('_current'=> true));
    }

    public function getRowUrl($row)
    {
        return 'javascript:void(0)';
    }


	

    public function callback_actioncolumn($value, $row, \Magento\Framework\DataObject $column, $isExport)
    {
        $html = '';

        $customer = $this->customerCollection->create()->load($value);

        if(isset($customer) && $customer->getId()) {
            $html .= "<div style='width: 100px;'>";
            $html .= "<div><a href='" . $this->getUrl( "customer/index/edit", array( 'id' => $value ) ) . "' target='_blank'>View</a></div>";
            $html .= "</div>";
        }

        return $html;
    }

    public function callback_assignedto($value, $row, \Magento\Framework\DataObject $column, $isExport)
    {
        $valueArray = array_filter(explode(',', $value));
        $html = '';

        $html .= '<div>';
        if(count($valueArray)) {
            $html .= '<ul>';
            foreach ($valueArray as $_value) {
                $html .= '<li>'. $this->_attrAssign->getOptionText($_value) .'</li>';
            }
            $html .= '</ul>';
        }
        $html .= '</div>';

        return $html;
    }

	

}