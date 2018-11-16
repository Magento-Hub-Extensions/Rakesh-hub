<?php
namespace Ueg\Crm\Block\Adminhtml\Amazon;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    

    /**
     * @var \Ueg\Crm\Model\Status
     */
    protected $_template = 'amazon/grid/extended.phtml';

    protected $authSession;

    protected $customerCollection;


    protected $_groupFactory;

    protected $_systemStore;

    protected $UserFactory;

    protected $CrmadminaclFactory;
    protected $SaleslogFactory;

    protected $AmazonFactory;

    protected $AmazonhistoryFactory;

    protected $priceHelper;

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
        \Magento\User\Model\UserFactory $UserFactory,
        \Ueg\Crm\Model\CrmadminaclFactory $CrmadminaclFactory,
        \Ueg\Crm\Model\SaleslogFactory $SaleslogFactory,
        \Ueg\Crm\Model\AmazonFactory $AmazonFactory,
        \Ueg\Crm\Model\AmazonhistoryFactory $AmazonhistoryFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        array $data = []
    ) {
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->customerCollection = $customerObject;
        $this->_groupFactory = $groupFactory;
        $this->_systemStore = $systemStore;
        $this->_storeManager = $context->getStoreManager();
        $this->UserFactory = $UserFactory;
        $this->CrmadminaclFactory = $CrmadminaclFactory;
        $this->SaleslogFactory = $SaleslogFactory;
        $this->AmazonFactory = $AmazonFactory;
        $this->AmazonhistoryFactory = $AmazonhistoryFactory;
        $this->priceHelper = $priceHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
          parent::_construct();
          $this->setId('AmazonSaleslogGrid');
          $this->setDefaultSort('date');
          $this->setDefaultDir('desc');
          $this->setUseAjax(true);
          $this->setSaveParametersInSession(true);
          $this->setDefaultLimit(20);
    }


    protected function _getStore()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        return $this->_storeManager->getStore($storeId);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }



    public function getHeaderText()
    {
        //return "Hot Clients";
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {


            //echo 'sadad';exit();

        
            $collection = $this->AmazonFactory->create()->getCollection();
            $this->setCollection($collection);
            return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $store = $this->_getStore();

		$this->addColumn('amazon_id', array(
      'header'    => __('ID'),
      'width'     => '50px',
      'index'     => 'amazon_id',
      'type'  => 'number',
      'column_css_class'=>'no-display',
      'header_css_class'=>'no-display',
      'frame_callback' => array($this, 'callback_idfield'),
    ));

    $this->addColumn('date', array(
      'header'    => __('Date'),
      'width'     => '50px',
      'index'     => 'date',
      'type'      => 'date',
    ));

    $this->addColumn('amazon_order_id', array(
      'header'    => __('Amazon Order ID'),
      'index'     => 'amazon_order_id',
    ));
    $this->addColumn('status', array(
      'header'    => __('Status'),
      'index'     => 'status',
    ));

    $this->addColumn('customer_name', array(
      'header'    => __('Customer Name'),
      'index'     => 'customer_name',
    ));

    /*$states = Mage::getModel('directory/country')->load('US')->getRegions();
    $options = array();
    foreach ($states as $state) {
      $options[$state->getName()] = $state->getName();
    }*/
    $this->addColumn('state', array(
      'header'    => __('State'),
      'index'     => 'state',
      //'type'      =>  'options',
      //'options'   =>  $options,
    ));

    $this->addColumn('product_name', array(
      'header'    => __('Product Name'),
      'index'     => 'product_name',
    ));
    $this->addColumn('sku', array(
      'header'    => __('SKU'),
      'index'     => 'sku',
    ));
    $this->addColumn('asn', array(
      'header'    => __('ASN'),
      'index'     => 'asn',
    ));

    $this->addColumn('qty_sold', array(
      'header'    => __('QTY Sold'),
      'width'     => '50px',
      'index'     => 'qty_sold',
      'type'  => 'number',
    ));

    $store = $this->_getStore();
    $this->addColumn('price', array(
      'header'    => __('Price'),
      'width'     => '50px',
      'index'     => 'price',
      'type'     => 'price',
      'currency_code' => $store->getBaseCurrency()->getCode(),
    ));

    $this->addColumn('cost', array(
      'header'    => __('Cost'),
      'width'     => '50px',
      'index'     => 'cost',
      'type'     => 'price',
      'currency_code' => $store->getBaseCurrency()->getCode(),
      'frame_callback' => array($this, 'callback_costfield'),
    ));
    $this->addColumn('amazon_fee', array(
      'header'    => __('Amazon Fee'),
      'width'     => '50px',
      'index'     => 'amazon_fee',
      'type'     => 'price',
      'currency_code' => $store->getBaseCurrency()->getCode()
    ));

    $this->addColumn('net_profit_dollar', array(
      'header'    => __('Net Profit ($)'),
      'width'     => '50px',
      'index'     => 'net_profit_dollar',
      'type'     => 'price',
      'currency_code' => $store->getBaseCurrency()->getCode(),
    ));

    $this->addColumn('net_profit_percentage', array(
      'header'    => __('Net Profit (%)'),
      'width'     => '50px',
      'index'     => 'net_profit_percentage',
      'type'  => 'number',
    ));


        $this->addExportType('crm/amazon/exportCsv', __('CSV'));
        $this->addExportType('crm/amazon/exportExcel', __('XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        parent::_prepareColumns();
        return $this;
    }

	

    public function getGridUrl()
    {
        return $this->getUrl('crm/amazon/grid', array('_current'=> true));
    }

    public function getRowUrl($row)
    {
        return 'javascript:void(0)';
    }


    public function callback_idfield($value, $row, $column, $isExport)
  {
    $html = '';

    $id = $row->getId();
    $fieldName = $column->getIndex();
    $html .= '<input type="hidden" name="amazon['.$id.']['.$fieldName.']" value="'.$value.'"/>';

    return $html;
  }

  public function callback_costfield($value, $row, $column, $isExport)
  {
    $html = '';

    $id = $row->getId();
    //echo $id.'===';exit();
    $fieldName = $column->getIndex();
    $value2 = ltrim($value, "$");
    $html .= '<div class="column-value">'.$value.'</div>';
    $html .= '<input type="text" name="amazon['.$id.'][data]['.$fieldName.']" class="text-box column-input" value="'.$value2.'" disabled="disabled" style="display:none;"/>';

    $collection = $this->AmazonhistoryFactory->create()->getCollection()
      ->addFieldToFilter('amazon_id', $id);
    $collection->getSelect()->order('modified_at ASC');
    if(count($collection) > 0) {
      $html .= "<span class='version_green_icon_span'><a class='version_green_icon' href='javascript:void(0)'><img src='". $this->getViewFileUrl('Ueg_Crm::images/normal.png') ."'></a></span>";
      $html .= "<div class='history_data' style='display:none;'>";
      $html .= "<div class='history_data_". $id ."'>";
      $html .= "<a class='version_close_icon' href='javascript:void(0);' title='Close'><span class='hl_close'>×</span></a>";
      $html .= "<ul>";
      foreach ($collection as $item) {
        $dt = new \DateTime( "now", new \DateTimeZone( "America/Los_Angeles" ) );
        $dt->setTimestamp(strtotime($item->getData('modified_at')));
        $html .= "<li>";
        $html .= "<span class='timestamp'>". $dt->format('m-d-y h:i:s A') ."</span>&nbsp;&nbsp;";
        $html .= "<span class='separator'>|</span>";
        $html .= "<strong>Rep:</strong> ". $item->getData('modified_user_name') ."&nbsp;&nbsp;&nbsp;";
        $html .= "<span class='separator'>|</span>";
        $html .= "<strong>Cost:</strong> ". $this->priceHelper->currency($item->getData('cost'), true, false) ."&nbsp;&nbsp;&nbsp;";
        $html .= "</li>";
      }
      $html .= "</ul>";
      $html .= "</div>";
      $html .= "</div>";
    }

    return $html;
  }

	

}