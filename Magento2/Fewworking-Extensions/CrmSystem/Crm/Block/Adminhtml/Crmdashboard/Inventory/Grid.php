<?php
namespace Ueg\Crm\Block\Adminhtml\Crmdashboard\Inventory;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    protected $_productFactory;

    protected $_template = 'crmdashboard/grid/extended.phtml';
   


    protected $_authSession;

    protected $_customerObject;

    protected $eavConfig;

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
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Customer\Model\CustomerFactory $customerObject,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->moduleManager = $moduleManager;
        $this->_authSession = $authSession;
        $this->_customerObject = $customerObject;
        $this->eavConfig = $eavConfig;
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context, $backendHelper, $data);
    }



    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = 0;
        return $this->_storeManager->getStore($storeId);
    }


    public function getHeaderText()
    {
      return "Inventory";
    }


    /**
     * @return void
     */
    protected function _construct()
    {
        $this->setId('crmInventoryGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('crmInventoryGridpost_filter');
        parent::_construct();
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
             $collection = $this->_productFactory->create()->getCollection()
                ->addAttributeToFilter('sugarcrm', 1)
                ->addAttributeToFilter('sku', array('neq' => ''))
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('price')
                ->addAttributeToSelect('tool_status')
                ->addAttributeToSelect('price_method')
                ->addAttributeToSelect('on_watch');
          $this->setCollection($collection);
          return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn("sku", array(
        "header" => __("SKU"),
        "index" => "sku",
        'width'     => '200px'
        ));
        $this->addColumn("name", array(
        "header" => __("Product Name"),
        "index" => "name",
        'filter_condition_callback' => array($this, '_filterProductNameCallback')
        ));
        $toolattribute = $this->eavConfig->getAttribute('catalog_product', 'tool_status');
        $toolStatusoptions = $toolattribute->getSource()->getAllOptions(false);

        $toolStatus = array();
        foreach ($toolStatusoptions as $option){
        $toolStatus[$option['value']] = $option['label'];
        }
        $this->addColumn('tool_status', array(
        'header'=> __('Status'),
        'index' => 'tool_status',
        'type'  => 'options',
        'options' => $toolStatus,
        ));

       

        $price_method = $this->eavConfig->getAttribute('catalog_product', 'price_method');
        $priceMethodoptions = $price_method->getSource()->getAllOptions(false);


        $priceMethod = array();
        foreach ($priceMethodoptions as $option){
        $priceMethod[$option['value']] = $option['label'];
        }
        $this->addColumn('price_method', array(
        'header'=> __('Method'),
        'index' => 'price_method',
        'type'  => 'options',
        'options' => $priceMethod,
        ));

        $store = $this->_getStore();
        $this->addColumn('price', array(
        'header'=> __('Price'),
        'type'  => 'price',
        'currency_code' => $store->getBaseCurrency()->getCode(),
        'index' => 'price',
        ));

        /*$this->addColumn('on_watch', array(
        'header'=> __('On Watch'),
        'index' => 'on_watch',
        'type'  => 'options',
        'options' => array(0 => 'No', 1 => 'Yes'),
        ));*/

        $this->addColumn('action',
        array(
          'header'    => __('Action'),
          'width'     => '50px',
          'filter'    => false,
          'sortable'  => false,
          'type'      => 'action',
          'getter'     => 'getEntityId',
          'actions'   => array(
          array(
              'caption' => __('View'),
              'url'     => array('base'=>'crm/crmproduct/index'),
              'field'   => 'id',
              'target'  => '_blank',
          )
        ),
        ));
        $this->addExportType($this->getUrl('crm/*/exportCsv', ['_current' => true]),__('CSV'));
        $this->addExportType($this->getUrl('crm/*/exportExcel', ['_current' => true]),__('Excel XML'));
        return parent::_prepareColumns();
    }

	
  
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('crm/*/indexInventory', ['_current' => true]);
    }

    /**
     * @param \Ueg\Crm\Model\asr|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return 'javascript:void(0)';
		
    }


    protected function _filterProductNameCallback($collection, $column)
    {
      if (!$value = $column->getFilter()->getValue()) {
        return $this;
      }
      if(isset($value) && !empty($value)) {
        $conditions = array();
        foreach (explode(" ", $value) as $keyword) {
          if(isset($keyword) && !empty($keyword)) {
            $conditions[] = "(cpev.value LIKE '%$keyword%' OR cpev.value LIKE '%$keyword' OR cpev.value LIKE '$keyword%')";
          }
        }
        $clause = implode(" AND ", $conditions);

        $productNameAttribute = $this->eavConfig->getAttribute('catalog_product', 'name');
        if($productNameAttribute) {
          $productNameAttributeId = $productNameAttribute->getId();

          $this->getCollection()->getSelect()->join( array( 'cpev' => 'catalog_product_entity_varchar' ), "(cpev.entity_id = e.entity_id) AND (cpev.attribute_id='" . $productNameAttributeId . "') AND (cpev.store_id=0)" );
          $this->getCollection()->getSelect()->where($clause);
          
        }
      }

      return $this;
    }

	

}