<?php
namespace Ueg\Crm\Block\Adminhtml\Saleslog;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    

    /**
     * @var \Ueg\Crm\Model\Status
     */
    protected $_template = 'saleslog/grid/extended.phtml';

    protected $authSession;

    protected $customerCollection;


    protected $_groupFactory;

    protected $_systemStore;

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
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
          parent::_construct();
          $this->setDefaultLimit(100);
          $this->setId('SaleslogGrid');
          $this->setDefaultSort('invoice_number');
          $this->setDefaultDir('desc');
          $this->setSaveParametersInSession(true);
          $this->setUseAjax(true);
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

        
      $collection = $this->SaleslogFactory->create()->getCollection();
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
        
		$this->addColumn('saleslog_id', array(
          'header'    => __('ID'),
          'width'     => '50px',
          'index'     => 'saleslog_id',
          'type'  => 'number',
          'column_css_class'=>'no-display',
          'header_css_class'=>'no-display',
          'frame_callback' => array($this, 'callback_idfield'),
      ));
      
      $this->addColumn('order_rep', array(
          'header'    => __('Sales'),
          'width'     => '50px',
          'index'     => 'order_rep',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('lot_number', array(
          'header'    => __('Source'),
          'width'     => '50px',
          'index'     => 'lot_number',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('order_date', array(
          'header'    => __('Transaction <br />Date'),
          'width'     => '50px',
          'index'     => 'order_date',
          'type'      => 'date',
          'frame_callback' => array($this, 'callback_datefield'),
      ));
      
      $this->addColumn('invoice_number', array(
          'header'    => __('Invoice #'),
          'width'     => '50px',
          'index'     => 'invoice_number',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('shipping_address', array(
          'header'    => __('eBay ID & <br /> Feedback Rating'),
          'width'     => '50px',
          'index'     => 'shipping_address',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('last_name', array(
          'header'    => __('Last Name'),
          'width'     => '50px',
          'index'     => 'last_name',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('first_name', array(
          'header'    => __('1st Name'),
          'width'     => '50px',
          'index'     => 'first_name',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('billing_city', array(
          'header'    => __('City'),
          'width'     => '50px',
          'index'     => 'billing_city',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('billing_state', array(
          'header'    => __('ST'),
          'width'     => '50px',
          'index'     => 'billing_state',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('description', array(
          'header'    => __('Description'),
          'width'     => '50px',
          'type'      => 'textarea',
          'index'     => 'description',
          'frame_callback' => array($this, 'callback_textareafield'),
      ));
      
      $this->addColumn('coin_number', array(
          'header'    => __('Coin #'),
          'width'     => '50px',
          'index'     => 'coin_number',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('phone', array(
          'header'    => __('Number'),
          'width'     => '50px',
          'index'     => 'phone',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
        $store = $this->_getStore();
      $this->addColumn('store_numis', array(
          'header'    => __('Store <br />Numis Sales'),
          'width'     => '50px',
          'index'     => 'store_numis',
          'type'     => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('ebay_bullion', array(
          'header'    => __('eBay <br />Bullion Sales'),
          'width'     => '50px',
          'index'     => 'ebay_bullion',
          'type'     => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('ebay_numis', array(
          'header'    => __('eBay <br />Numis Sales'),
          'width'     => '50px',
          'index'     => 'ebay_numis',
          'type'     => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('shipping', array(
          'header'    => __('Ship/Ins <br />Charge'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'shipping',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('store_bullion', array(
          'header'    => __('Store <br />Bullion'),
          'width'     => '50px',
          'index'     => 'store_bullion',
          'type'     => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('qb_shipvia', array(
          'header'    => __('Ship <br />by'),
          'width'     => '50px',
          'index'     => 'qb_shipvia',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('emails', array(
          'header'    => __('Email Address'),
          'width'     => '50px',
          'index'     => 'emails',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('payment_received', array(
          'header'    => __('Payment<br />Received'),
          'width'     => '50px',
          'index'     => 'payment_received',
          'type'      => 'date',
          'frame_callback' => array($this, 'callback_datefield'),
      ));
      
      $this->addColumn('projected_shipdate', array(
          'header'    => __('Projected<br />Ship Date'),
          'width'     => '50px',
          'index'     => 'projected_shipdate',
          'frame_callback' => array($this, 'callback_textfield'),
      ));
      
      $this->addColumn('qb_shipdate', array(
          'header'    => __('Date<br />Shipped'),
          'width'     => '50px',
          'index'     => 'qb_shipdate',
          'type'      => 'date',
          'frame_callback' => array($this, 'callback_datefield'),
      ));



        $admin_user_session = $this->authSession;
        $adminuserId = $admin_user_session->getUser()->getUserId();
        $role_id = $this->UserFactory->create()->load($adminuserId)->getRole()->getData('role_id');
        $model = $this->CrmadminaclFactory->create()->load($role_id, "role_id");
        $sales_log_delete = $model->getData('sales_log_delete');

        if(isset($sales_log_delete) && $sales_log_delete == 1) {
        $this->addColumn(
          'action',
          array(
            'header'         => __( 'Action' ),
            'index'          => 'saleslog_id',
            'filter'         => false,
            'sortable'       => false,
            'frame_callback' => array( $this, 'callback_actioncolumn' ),
          )
        );
        }

        $this->addExportType('crm/saleslog/exportCsv', __('CSV'));
        $this->addExportType('crm/saleslog/exportExcel', __('XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        parent::_prepareColumns();
        return $this;
    }

	

    public function getGridUrl()
    {
        return $this->getUrl('crm/saleslog/grid', array('_current'=> true));
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
        $html .= '<input type="hidden" name="sales['.$id.']['.$fieldName.']" value="'.$value.'"/>';

        return $html;
    }

    public function callback_textfield($value, $row, $column, $isExport)
    {
        $html = '';

        $id = $row->getId();
        $fieldName = $column->getIndex();
        $html .= '<div class="column-value">'.$value.'</div>';
        $html .= '<input type="text" name="sales['.$id.'][data]['.$fieldName.']" class="text-box column-input" value="'.$value.'" disabled="disabled" style="display:none;"/>';

        return $html;
    }

    public function callback_textareafield($value, $row, $column, $isExport)
    {
        $html = '';

        $id = $row->getId();
        $fieldName = $column->getIndex();
        $html .= '<div class="column-value">'.$value.'</div>';
        $html .= '<textarea name="sales['.$id.'][data]['.$fieldName.']" class="textarea-box column-input" disabled="disabled" style="width:98%; display:none;">'.$value.'</textarea>';

        return $html;
    }

    public function callback_selectfield($value, $row, $column, $isExport)
    {
        $html = '';

        $id = $row->getId();
        $fieldName = $column->getIndex();
        $options = $column->getOptions();

        $html .= '<div class="column-value">'.$value.'</div>';
        $html .= "<select name='sales[$id][data][$fieldName]' class='select-box column-input' disabled='disabled' style='display:none;'>";
        foreach ($options as $option) {
            $selected = "";
            if($value == $option) {
                $selected = "selected='selected'";
            }
            $html .= "<option value='$option' $selected>$option</option>";
        }
        $html .= "</select>";

        return $html;
    }

    public function callback_actioncolumn($value, $row, $column, $isExport)
    {
        $html = '';

        $id = $row->getId();
        $html .= "<div style='width: 100px;'>";
        $html .= "<div><input type='checkbox' name='sales[$id][delete]' value='1'/> Delete</div>";
        $html .= "</div>";

        return $html;
    }

    public function callback_datefield($value, $row, $column, $isExport)
    {
        $html = '';

        $id = $row->getId();
        $fieldName = $column->getIndex();
        $dateValue = (isset($value) && !empty($value)) ? date('m/j/y', strtotime($value)) : "";
        $html .= '<div class="column-value">'.$value.'</div>';
        $html .= '<input id="request-date-'.$fieldName.'-'.$id.'" type="text" name="sales['.$id.'][data]['.$fieldName.']" class="text-box column-input" value="'.$dateValue.'" disabled="disabled" style="width:95%; display:none;"/>';

        $html .= '<script type="text/javascript">
                    require([
                          "jquery",
                          "mage/calendar"
                            ], function($,calendar){
                                 $("#'.'request-date-'.$fieldName.'-'.$id.'").calendar({
                                          dateFormat: "M/d/yy",
                                          showsTime: false,
                                          timeFormat: "HH:mm:ss",
                                          sideBySide: true,
                                          closeText: "Done",
                                          selectOtherMonths: true,
                                          onClose: function( selectedDate ) {
                                            $( "#'.'request-date-'.$fieldName.'-'.$id.'" ).datepicker( "option", "minDate", selectedDate );
                                          }
                              });
                              });
                              </script>';

        return $html;
    }


     protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('saleslog');

        $store = $this->_getStore();
    
    //Cancelled   
        $this->getMassactionBlock()->addItem('cancelled', array(
             'label'=> __('Cancelled'),
             'url'  => $this->getUrl('*/*/massCancelled', array('_current'=>true))
        ));
    
    //Refunded    
        $this->getMassactionBlock()->addItem('refunded', array(
             'label'=> __('Refunded'),
             'url'  => $this->getUrl('*/*/massRefunded', array('_current'=>true))
        ));
    
    //Returned    
        $this->getMassactionBlock()->addItem('returned', array(
             'label'=> __('Returned'),
             'url'  => $this->getUrl('*/*/massReturned', array('_current'=>true))
        ));
    
    //Fraud   
        $this->getMassactionBlock()->addItem('fraud', array(
             'label'=> __('Fraud'),
             'url'  => $this->getUrl('*/*/massFraud', array('_current'=>true))
        ));
    
    //On Hold   
        $this->getMassactionBlock()->addItem('on_hold', array(
             'label'=> __('On Hold'),
             'url'  => $this->getUrl('*/*/massHold', array('_current'=>true))
        ));
    
    //Storage   
        $this->getMassactionBlock()->addItem('storage', array(
             'label'=> __('Storage'),
             'url'  => $this->getUrl('*/*/massStorage', array('_current'=>true))
        ));
    
    //Lay a way   
        $this->getMassactionBlock()->addItem('layaway', array(
             'label'=> __('Lay-A-Way'),
             'url'  => $this->getUrl('*/*/masslayaway', array('_current'=>true))
        ));

        return $this;
    }

	

}