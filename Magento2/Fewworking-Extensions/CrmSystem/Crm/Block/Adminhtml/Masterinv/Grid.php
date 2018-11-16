<?php
namespace Ueg\Crm\Block\Adminhtml\Masterinv;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    

    /**
     * @var \Ueg\Crm\Model\Status
     */
    protected $_template = 'masterinv/grid/extended.phtml';

    protected $authSession;

    protected $customerCollection;


    protected $_groupFactory;

    protected $_systemStore;

    protected $_coin;

    protected $_eavAttribute;
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
         \Ueg\Crm\Model\Coinoptions $coinOptions,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $attribute,
        \Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerAssignedToOptions $AssignOption,
        array $data = []
    ) {
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->customerCollection = $customerObject;
        $this->_groupFactory = $groupFactory;
        $this->_eavAttribute = $attribute;
        $this->_systemStore = $systemStore;
        $this->_coin = $CoinFactory;
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
        $this->setId('masterinvGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('masterinvpost_filter');
    }


    public function getHeaderText()
    {
        return "Client Master Inventory";
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {

        
        $collection = $this->_coin->create()->getCollection();
        $collection->getSelect()->join(
            array('ce'=>'customer_entity'),
            'main_table.customer_id = ce.entity_id',
            array('email')
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

        $this->addColumn(
            'pcgs',
            array(
                'header' => __('PCGS#'),
                'index' => 'pcgs',
            )
        );

        $this->addColumn(
            'year',
            array(
                'header' => __('Year'),
                'index' => 'year',
            )
        );

        $mints = $coinOptions->getMintOptions();
        $mintOptions = array();
        foreach ($mints as $_mint) {
            $mintOptions[$_mint] = $_mint;
        }
        $this->addColumn(
            'mint',
            array(
                'header' => __('Mint'),
                'index' => 'mint',
                'type'      =>  'options',
                'options'   =>  $mintOptions,
            )
        );

        $this->addColumn(
            'variety',
            array(
                'header' => __('Variety'),
                'index' => 'variety',
            )
        );

        $desig = $coinOptions->getDesigOptions();
        $desigOptions = array();
        foreach ($desig as $_desig) {
            $desigOptions[$_desig] = $_desig;
        }
        $this->addColumn(
            'desig',
            array(
                'header' => __('Desig'),
                'index' => 'desig',
                'type'      =>  'options',
                'options'   =>  $desigOptions,
            )
        );

        $this->addColumn(
            'date_requested',
            array(
                'header' => __('Date Requested'),
                'index' => 'date_requested',
                'type'      => 'date',
                //'gmtoffset' => true,
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

        $grade = $coinOptions->getGradeOptions();
        $gradeOptions = array();
        foreach ($grade as $_grade) {
            $gradeOptions[$_grade] = $_grade;
        }
        $this->addColumn(
            'min_grade',
            array(
                'header' => __('Min Grade'),
                'index' => 'min_grade',
                'type'      =>  'options',
                'options'   =>  $gradeOptions,
            )
        );

        $this->addColumn(
            'max_grade',
            array(
                'header' => __('Max Grade'),
                'index' => 'max_grade',
                'type'      =>  'options',
                'options'   =>  $gradeOptions,
            )
        );

        $status = $coinOptions->getStatusOptions();
        $statusOptions = array();
        foreach ($status as $_status) {
            $statusOptions[$_status] = $_status;
        }
        $this->addColumn(
            'status',
            array(
                'header' => __('Status'),
                'index' => 'status',
                'type'      =>  'options',
                'options'   =>  $statusOptions,
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
            'client_code',
            array(
                'header' => __('Client Code'),
                'index' => 'client_code',
            )
        );

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
            'coin_type',
            array(
                'header' => __('Is Collection'),
                'index' => 'coin_type',
                'type'      =>  'options',
                'options'   =>  array(1 => 'No', 2 => 'Yes'),
            )
        );

        $this->addColumn(
            'hot_client',
            array(
                'header' => __('Hot Client'),
                'index' => 'hot_client',
                'type'      =>  'options',
                'filter_condition_callback' => array($this, '_customhotClientFilterCallBack'),
                'options'   =>  array(0 => 'No', 1 => 'Yes'),
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
        //$this->addExportType('crm/*/exportExcel', __('XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        parent::_prepareColumns();
        return $this;
    }

	

    public function getGridUrl()
    {
        return $this->getUrl('crm/masterinv/grid', array('_current'=> true));
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


        protected function _customhotClientFilterCallBack($collection, $column)
    {
            $value = $column->getFilter()->getValue();
            $this->getCollection()->getSelect()->where("cei_hc.value = ?", $value);
            return $this;
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