<?php
namespace Ueg\Crm\Block\Adminhtml\Account;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    protected $_productFactory;

    protected $_template = 'account/grid/extended.phtml';
   


    protected $_authSession;

    protected $_customerObject;

    protected $roles;

    protected $eavConfig;

    protected $connection;
    protected $_resource;

    protected $_attrOptions;

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
        \Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerAssignedToOptions $attrOptions,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->moduleManager = $moduleManager;
        $this->_authSession = $authSession;
        $this->_customerObject = $customerObject;
        $this->eavConfig = $eavConfig;
        $this->roles = $roleObject;
        $this->_resource = $resource;
        $this->_attrOptions  = $attrOptions;
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
        $this->setUseAjax(false);
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
          //echo $role_id;exit();
          // if($role_id != 1) {
          if($role_id != 6) { // if not Administrators, show assigned_to accounts only
          $collection->addAttributeToFilter(array(
          array(
          'attribute' => 'assigned_to',
          'finset' => $adminuserId),
          array(
          'attribute' => 'entity_id',
          'in' => $customerIds)
          ));
          }

         // echo $collection->getSelect()->__toString();exit();
          
          $this->setCollection($collection);

          return parent::_prepareCollection();
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

          $assignedOption = $this->_attrOptions->getOptionArray();
          array_shift($assignedOption);
          $assignedOptions = array();
          foreach ($assignedOption as $key => $_assignedOption) {
            $assignedOptions[$key] = $_assignedOption;
          }


          $this->addColumn(
            'assigned_to',
            array(
              'header' => __('Assigned To'),
              'index' => 'assigned_to',
              'filter' => false,
              'sortable' => false,
              'frame_callback' => array($this, 'callback_assignedto'),
            )
          );
          $this->addColumn(
            'admin_only',
            array(
              'header' => __('Admin Only'),
              'index' => 'admin_only',
              'type'      =>  'options',
              'options'   =>  array(0 => "No", 1 => "Yes"),
            )
          );
        
          $this->addColumn('name', array(
            'header'    => __('Name'),
            'index'     => 'name'
          ));
          $this->addColumn('email', array(
            'header'    => __('Email'),
            'width'     => '150',
            'index'     => 'email'
          ));


          $this->addColumn('items', array(
           'header'    => __('Items'),
           'index'     => 'entity_id',
           'frame_callback' => array($this, 'callback_itemscolumn'),
           'filter_condition_callback' => array($this, 'callback_itemsfilter'),
        ));

          $this->addColumn('telephone', array(
            'header'    => __('Telephone'),
            'width'     => '100',
            'index'     => 'billing_telephone'
          ));

          $this->addColumn('city', array(
            'header'    => __('City'),
            'width'     => '100',
            'index'     => 'billing_city'
          ));

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
              'frame_callback' => array($this, 'callback_actioncolumn'),
            )
          );

          $this->addExportType('*/*/exportCsv', __('CSV'));
          $this->addExportType('*/*/exportExcel', __('XML'));


        return parent::_prepareColumns();
    }

	
  
 public function getGridUrl()
      {
        return $this->getUrl('crm/account/index', array('_current'=> true));
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

  public function callback_assignedto($value, $row, $column, $isExport)
  {
    $valueArray = array_filter(explode(',', $value));
    $html = '';

    $html .= '<div>';
    if(count($valueArray)) {
      $html .= '<ul>';
      foreach ($valueArray as $_value) {
        $html .= '<li>'. $this->_attrOptions->getOptionText($_value) .'</li>';
      }
      $html .= '</ul>';
    }
    $html .= '</div>';

    return $html;
  }



    public function callback_itemscolumn($value, $row, $column, $isExport)
    {
       $html = "-";

       if ($fvalue = $column->getFilter()->getValue()) {
          $readConnection  = $this->_resource->getConnection();
          $crmInvoice = $this->_resource->getTableName( 'ueg_crm_invoice' );
          $crmInvoiceItem = $this->_resource->getTableName( 'ueg_crm_invoice_item' );

          if(isset($fvalue) && !empty($fvalue)) {
             $whereIClauseItems = array();
             foreach(explode(" ", $fvalue) as $keyword) {
                if(isset($keyword) && !empty($keyword)) {
                   $whereIClauseItems[] = "(cii.item_name LIKE '%$keyword%' OR cii.item_name LIKE '%$keyword' OR cii.item_name LIKE '$keyword%')";
                }
             }

             $sql = "";
             $whereIClause = "";
             if(isset($whereIClauseItems) && !empty($whereIClauseItems) && count($whereIClauseItems) > 0) {
                $whereIClause = implode(' AND ', $whereIClauseItems);
                $sql = "SELECT cii.parent_id, cii.item_name, ci.magento_customer_id FROM $crmInvoiceItem AS cii INNER JOIN $crmInvoice AS ci ON ci.invoice_id = cii.parent_id WHERE ". $whereIClause . " AND ci.magento_customer_id != 0";
                $result = $readConnection->fetchAll($sql);


                $itemArray = array();
                if(isset($result) && !empty($result)) {
                   foreach ($result as $item) {
                      $customerId = $item['magento_customer_id'];
                      $itemArray[$customerId][] = $item['item_name'];
                   }
                }

                if(isset($itemArray[$value]) && !empty($itemArray[$value])) {
                   $items = array_unique($itemArray[ $value ]);
                   $html = "<ul>";
                   foreach ($items as $name) {
                      $html .= "<li>$name</li>";
                   }
                   $html .= "</ul>";
                }
             }
          }
       }

       return $html;
    }

	protected function callback_itemsfilter($collection, $column)
	{
		if (!$value = $column->getFilter()->getValue()) {
			return $this;
		}

		$readConnection  = $this->_resource->getConnection();
		$crmInvoice = $this->_resource->getTableName( 'ueg_crm_invoice' );
		$crmInvoiceItem = $this->_resource->getTableName( 'ueg_crm_invoice_item' );

		if(isset($value) && !empty($value)) {
			$whereIClauseItems = array();
			foreach(explode(" ", $value) as $keyword) {
				if(isset($keyword) && !empty($keyword)) {
					$whereIClauseItems[] = "(cii.item_name LIKE '%$keyword%' OR cii.item_name LIKE '%$keyword' OR cii.item_name LIKE '$keyword%')";
				}
			}

			$sql = "";
			$whereIClause = "";
			if(isset($whereIClauseItems) && !empty($whereIClauseItems) && count($whereIClauseItems) > 0) {
				$whereIClause = implode(' AND ', $whereIClauseItems);
				$sql = "SELECT cii.parent_id, cii.item_name, ci.magento_customer_id FROM $crmInvoiceItem AS cii INNER JOIN $crmInvoice AS ci ON ci.invoice_id = cii.parent_id WHERE ". $whereIClause . " AND ci.magento_customer_id != 0";
				$result = $readConnection->fetchAll($sql);

				//Mage::log('Sql: '. $sql, null, 'abc_filter.log');
				//Mage::log('Result: '. print_r($result, true), null, 'abc_filter.log');

				$customerIds = array();
				if(isset($result) && !empty($result)) {
					foreach ($result as $item) {
						$customerIds[] = $item['magento_customer_id'];
					}
				}

				if(isset($customerIds)) {
					$customerIds = array_unique($customerIds);
					$this->getCollection()->addAttributeToFilter( 'entity_id', array( 'in' => $customerIds ) );
				}
			}
		}

		return $this;
	}

}