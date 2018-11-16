<?php
namespace Ueg\Amazon\Block\Adminhtml\Amazon;


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

    protected $productFactory;

    protected $resource;

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
        \Ueg\Crm\Model\AmazonFactory $CrmamazonFactory,
        \Ueg\Crm\Model\AmazonhistoryFactory $CrmamazonhistoryFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\ResourceConnection $resource,
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
        $this->AmazonFactory = $CrmamazonFactory;
        $this->AmazonhistoryFactory = $CrmamazonhistoryFactory;
        $this->priceHelper = $priceHelper;
        $this->productFactory = $productFactory;
        $this->resource = $resource;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
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

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {

            $store = $this->_getStore();
        $collection = $this->productFactory->create()->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sell_amazon')
            ->addAttributeToSelect('ready_amazon')
            ->addAttributeToSelect('amazon_description')
            ->addAttributeToSelect('amazon_item_type')
            ->addAttributeToSelect('amazon_update_delete')
            ->addAttributeToSelect('amazon_qty')
            ->addAttributeToSelect('amazon_launch_date')
            ->addAttributeToSelect('amazon_handling_time')
            ->addAttributeToSelect('amazon_tax_code')
            ->addAttributeToSelect('amazon_sale_price')
            ->addAttributeToSelect('amazon_sale_start')
            ->addAttributeToSelect('amazon_sale_end')
            ->addAttributeToSelect('amazon_max_aggregate')
            ->addAttributeToSelect('amazon_gift_message')
            ->addAttributeToSelect('amazon_giftwrap')
            ->addAttributeToSelect('amazon_shipping_uom')
            ->addAttributeToSelect('amazon_item_weight')
            ->addAttributeToSelect('amazon_total_uom')
            ->addAttributeToSelect('amazon_bullet_1')
            ->addAttributeToSelect('amazon_bullet_2')
            ->addAttributeToSelect('amazon_bullet_3')
            ->addAttributeToSelect('amazon_bullet_4')
            ->addAttributeToSelect('amazon_bullet_5')
            ->addAttributeToSelect('amazon_search_one')
            ->addAttributeToSelect('amazon_search_two')
            ->addAttributeToSelect('amazon_search_three')
            ->addAttributeToSelect('amazon_search_four')
            ->addAttributeToSelect('amazon_search_five')
            ->addAttributeToSelect('amazon_obverse')
            ->addAttributeToSelect('amazon_reverse')
            ->addAttributeToSelect('amazon_url_one')
            ->addAttributeToSelect('amazon_url_two')
            ->addAttributeToSelect('amazon_url_three')
            ->addAttributeToSelect('amazon_url_four')
            ->addAttributeToSelect('amazon_url_five')
            ->addAttributeToSelect('amazon_url_six')
            ->addAttributeToSelect('amazon_url_seven')
            ->addAttributeToSelect('amazon_url_eight')
            ->addAttributeToSelect('amazon_center_id')
            ->addAttributeToSelect('amazon_shipping_height')
            ->addAttributeToSelect('amazon_package_width')
            ->addAttributeToSelect('amazon_package_legth')
            ->addAttributeToSelect('amazon_package_uom')
            ->addAttributeToSelect('amazon_package_weight')
            ->addAttributeToSelect('amazon_package_weight_uom')
            ->addAttributeToSelect('amazon_country_origin')
            ->addAttributeToSelect('amazon_denomination')
            ->addAttributeToSelect('amazon_coin_series')
            ->addAttributeToSelect('amazon_grade')
            ->addAttributeToSelect('amazon_set')
            ->addAttributeToSelect('amazon_set_count')
            ->addAttributeToSelect('amazon_designation')
            ->addAttributeToSelect('amazon_edge_style')
            ->addAttributeToSelect('amazon_designer')
            ->addAttributeToSelect('amazon_mintage')
            ->addAttributeToSelect('amazon_composition_one')
            ->addAttributeToSelect('amazon_composition_two')
            ->addAttributeToSelect('amazon_composition_three')
            ->addAttributeToSelect('amazon_composition_four')
            ->addAttributeToSelect('amazon_diameter')
            ->addAttributeToSelect('amazon_total_diameter')
            ->addAttributeToSelect('amazon_metal_weight')
            ->addAttributeToSelect('amazon_total_weight_uom')
            ->addAttributeToSelect('amazon_fineness')
            ->addAttributeToSelect('market4_price')
            ->addAttributeToSelect('weight')      
            ->addAttributeToSelect('amazon_mint_mark')
            ->addAttributeToSelect('mint')
            ->addAttributeToSelect('year')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('product_name')
            ->addAttributeToSelect('cost')
            ->addAttributeToSelect('amazon_grading_service')
            ->addAttributeToSelect('grading_service')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('amazon_asin')
            ->addAttributeToSelect('amazon_fnsku')
            ->addAttributeToSelect('amazon_qty')
            ->addAttributeToSelect('metal_weight')      
            ->addAttributeToSelect('metal')       
            ->addAttributeToSelect('amazon_new')      
            ->addAttributeToSelect('replacement_cost_method')     
            ->addAttributeToSelect('replacement_premium')       
            ->addAttributeToSelect('cost_spot')     
            ->joinField('qty',
                'cataloginventory_stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
         $collection->addFieldToFilter('sell_amazon', 1);
         $collection->addFieldToFilter('amazon_new', 1);

        if ($store->getId()) {
            $adminStore = $this->_storeManager->getStore()->getId();
            $collection->addStoreFilter($store);
            $collection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner', $adminStore);
            $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
        }
        else {
            $collection->addAttributeToSelect('price');
            $collection->addAttributeToSelect('status');
            $collection->addAttributeToSelect('visibility');
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }



     protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField('websites',
                    'catalog/product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $store = $this->_getStore();

		    $this->addColumn('entity_id', array(
          'header'    => __('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'entity_id',
      ));

    
        $this->addColumn('sku',
            array(
                'header'=> __('SKU'),
                'width' => '80px',
                'index' => 'sku',
        ));

        $this->addColumn('product_name',
            array(
                'header'=> __('Product Name'),
                'width' => '180px',
                'index' => 'product_name',
        ));

        $this->addColumn('amazon_asin',
            array(
                'header'=> __('ASIN'),
                'width' => '180px',
                'index' => 'amazon_asin',
        ));

        $this->addColumn('amazon_fnsku',
            array(
                'header'=> __('FN Sku'),
                'width' => '180px',
                'index' => 'amazon_fnsku',
        ));

        $this->addColumn('amazon_qty',
            array(
                'header'=> __('Amazon Qty'),
                'width' => '180px',
                'index' => 'amazon_qty',
        ));

      $this->addColumn('metal', array(
          'header'    => __('Metal'),
          'index'     => 'metal',
          'type'     => 'options',
          'options' => array('13'=>'GD','12'=>'SI', '11'=>'PL', '10'=>'PA', '57'=>'NI', '56'=>'CO', '55'=>'ST', '123'=>'MX'),
      ));

        $this->addColumn('metal_weight',
            array(
                'header'=> __('Metal Weight'),
                'width' => '180px',
                'index' => 'metal_weight',
        ));

      $this->addColumn('replacement_cost_method', array(
          'header'    => __('Replacement Cost Method'),
          'index'     => 'replacement_cost_method',
          'type'     => 'options',
          'options' => array('2753'=>'Fixed', '2751'=>'Spot + $','2752'=>'Spot + %'),
      ));

        $this->addColumn('replacement_premium',
            array(
                'header'=> __('Replacement Premium'),
                'width' => '180px',
                'index' => 'replacement_premium',
        'type' => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
        ));

        $this->addColumn('cost_spot',
            array(
                'header'=> __('Spot'),
                'width' => '180px',
                'index' => 'cost_spot',
                'type' => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
        ));

      $this->addColumn('amazon_new', array(
          'header'    => __('New Item'),
          'index'     => 'amazon_new',
          'type'     => 'options',
          'options' => array('1'=>'Yes','0'=>'No'),
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


        $this->addExportType('amazon/amazon/exportCsv', __('CSV'));
        $this->addExportType('amazon/amazon/exportExcel', __('XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        parent::_prepareColumns();
        return $this;
    }


     public function callback_actioncolumn($value, $row, \Magento\Framework\DataObject $column, $isExport)
    {
            $html = '';
            $html .= "<div style='width: 100px;'>";
            $html .= "<div><a href='" . $this->getUrl( "catalog/product/edit/", array( 'id' => $value ) ) . "' target='_blank'>View</a></div>";
            $html .= "</div>";

        return $html;
    }

	

    public function getGridUrl()
    {
        return $this->getUrl('amazon/amazon/grid', array('_current'=> true));
    }

      protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');
        $this->getMassactionBlock()->addItem('amazon_new', array(
             'label'=> __('Remove From Grid'),
             'url'  => $this->getUrl('amazon/amazon/massAmazon', array('_current'=>true)),
        ));

        return $this;
    }
  protected function _customProductNameFilter($collection, $column)
  {
      if (!$value = $column->getFilter()->getValue()) {
        return $this;
      }
      $tbl_product_varchar = $this->resource->getTableName('catalog_product_entity_varchar');
      $terms=explode(' ', $value);
      foreach ($terms as $term) {
        $search_terms[]="'%".$term."%'";
      }
      $search=implode(' AND at_name.value LIKE ', $search_terms);
      $this->getCollection()->getSelect()->join(array('at_name' => $tbl_product_varchar),'(e.entity_id = at_name.entity_id) AND (at_name.attribute_id = 265) AND (at_name.store_id = 0)','at_name.value AS name')
                                         ->where("at_name.value LIKE " . $search);
      return $this;
  }

  public function getRowUrl($row)
  {
      return $this->getUrl('catalog/product/edit', array(
                                                             'id' => $row->getId()
                                                             )
                           );
  }



}