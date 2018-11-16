<?php
namespace Ueg\Crm\Block\Adminhtml\Crmdashboard\Account;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    protected $_productFactory;

    protected $_template = 'crmdashboard/grid/extendedaccount.phtml';
   


    protected $_authSession;

    protected $_customerObject;

    protected $roles;

    protected $eavConfig;

    protected $connection;
    protected $_resource;

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
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Authorization\Model\RoleFactory $roleObject,
        \Magento\Customer\Model\CustomerFactory $customerObject,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->moduleManager = $moduleManager;
        $this->_authSession = $authSession;
        $this->_customerObject = $customerObject;
        $this->eavConfig = $eavConfig;
        $this->roles = $roleObject;
        $this->_resource = $resource;
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
      return "My Accounts";
    }


    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('customerGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('customerpost_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
          $adminuserId = $this->_authSession->getUser()->getUserId();

          $roleNameObject = $this->roles->create()->load($adminuserId,'user_id');

          $role_id = $roleNameObject->getRoleId();

          
          $readConnection  = $this->_resource->getConnection( 'core_read' );
          $crmInvoice     = $this->_resource->getTableName( 'ueg_crm_invoice' );


          $sql = "SELECT DISTINCT magento_customer_id FROM $crmInvoice WHERE find_in_set($adminuserId, assigned_user_id) <> 0 AND magento_customer_id != 0";
          
          $customerIds = $readConnection->fetchCol($sql);
          

          $collection = $this->_customerObject->create()->getCollection()
                  ->addNameToSelect()
                  ->addAttributeToSelect('email')
                  ->addAttributeToSelect('created_at')
                  ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
                  ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
                  ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
                  ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
                  ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
                  ->joinAttribute('assigned_to', 'customer/assigned_to', 'entity_id', null, 'left')
                  ->joinAttribute('admin_only', 'customer/admin_only', 'entity_id', null, 'left');

          if($role_id != 6) {
          $collection->addAttributeToFilter(array(
          array(
          'attribute' => 'assigned_to',
          'finset' => $adminuserId),
          array(
          'attribute' => 'entity_id',
          'in' => $customerIds)
          ));
          }
          
          $this->setCollection($collection);

          return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
          $this->addColumn('name', array(
          'header'    => __('Name'),
          'index'     => 'name'
          ));
          $this->addColumn('email', array(
          'header'    => __('Email'),
          'width'     => '150',
          'index'     => 'email'
          ));

          $this->addColumn('telephone', array(
          'header'    => __('Telephone'),
          'width'     => '100',
          'index'     => 'billing_telephone'
          ));

          /*$this->addColumn('city', array(
          'header'    => __('City'),
          'width'     => '100',
          'index'     => 'billing_city'
          ));*/

          $this->addColumn('billing_region', array(
          'header'    => __('State'),
          'width'     => '100',
          'index'     => 'billing_region',
          ));

          $this->addColumn('billing_postcode', array(
          'header'    => __('ZIP'),
          'width'     => '90',
          'index'     => 'billing_postcode',
          ));

          $this->addColumn(
          'action',
          array(
            'header' => __('Action'),
            'index' => 'entity_id',
            'filter' => false,
            'sortable' => false,
            'width'     => '50px',
            'frame_callback' => array($this, 'callback_actioncolumn'),
          )
          );

        $this->addExportType($this->getUrl('crm/*/exportCsvAccount', ['_current' => true]),__('CSV'));
        $this->addExportType($this->getUrl('crm/*/exportExcelAccount', ['_current' => true]),__('Excel XML'));


        return parent::_prepareColumns();
    }

	
  
    public function getGridUrl()
    {
      return $this->getUrl('crm/*/indexAccount', array('_current'=> true));
    }

    public function getRowUrl($row)
    {
      return "javascript:void(0)";
    }

    public function callback_actioncolumn($value, $row, $column, $isExport)
    {
      $html = '';

      $html .= '<div class="action" style="text-align: center;">';
      $html .= '<span><a class="grid-preview" href="'.$this->getUrl("crm/crmcustomer/view", array('id'=>$value)).'" target="_blank">View</a></span>';
      $html .= '</div>';

      return $html;
    }

}