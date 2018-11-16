<?php
namespace Ueg\Crm\Block\Adminhtml\Crm;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_productCollectionFactory;
    protected $eavConfig;
    protected $_storeManager;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Ueg\Crm\Model\crmadminaclFactory $crmadminaclFactory
     * @param \Ueg\Crm\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
            \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
         \Magento\Eav\Model\Config $eavConfig,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->eavConfig = $eavConfig;
        $this->_storeManager = $context->getStoreManager();

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('crmGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        // $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_productCollectionFactory->create()
                            ->addAttributeToFilter('sugarcrm', 1)
                            ->addAttributeToFilter('sku', array('neq' => ''))
                            ->addAttributeToSelect('sku')
                            ->addAttributeToSelect('name')
                            ->addAttributeToSelect('price')
                            ->addAttributeToSelect('tool_status')
                            ->addAttributeToSelect('price_method')
                            ->addAttributeToSelect('on_watch');

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
        $this->addColumn("entity_id", array(
            "header" => __("ID"),
            "align" =>"right",
            "width" => "50px",
            "type" => "number",
            "index" => "entity_id",
        ));
        $this->addColumn("sku", array(
            "header" => __("SKU"),
            "index" => "sku",
        ));
        $this->addColumn("name", array(
            "header" => __("Product Name"),
            "index" => "name",
             'filter_condition_callback' => array($this, '_filterProductNameCallback')
        ));

        $tool_status = $this->eavConfig->getAttribute('catalog_product', 'tool_status');
        $options = $tool_status->getSource()->getAllOptions();
        $toolStatus = array();
        foreach ($options as $option){
            $toolStatus[$option['value']] = $option['label'];
        }
        $this->addColumn('tool_status', array(
            'header'=> __('Status'),
            'index' => 'tool_status',
            'type'  => 'options',
            'options' => $toolStatus,
        ));

        $price_method = $this->eavConfig->getAttribute('catalog_product', 'price_method');
        $options = $price_method->getSource()->getAllOptions();

        $priceMethod = array();
        foreach ($options as $option){
            $priceMethod[$option['value']] = $option['label'];
        }
        $this->addColumn('price_method', array(
            'header'=> __('Pricing Method'),
            'index' => 'price_method',
            'type'  => 'options',
            'options' => $priceMethod,
        ));

        $store = $this->_storeManager->getStore();
        $this->addColumn('price', array(
            'header'=> __('MSG Price'),
            'type'  => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
        ));

        $this->addColumn('on_watch', array(
            'header'=> __('On Watch'),
            'index' => 'on_watch',
            'type'  => 'options',
            'options' => array(0 => 'No', 1 => 'Yes'),
        ));

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

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	
    /**
     * @return $this
     */
    // protected function _prepareMassaction()
    // {

    //     $this->setMassactionIdField('crmacl_id');
    //     //$this->getMassactionBlock()->setTemplate('Ueg_Crm::crmadminacl/grid/massaction_extended.phtml');
    //     $this->getMassactionBlock()->setFormFieldName('crmadminacl');

    //     $this->getMassactionBlock()->addItem(
    //         'delete',
    //         [
    //             'label' => __('Delete'),
    //             'url' => $this->getUrl('crm/*/massDelete'),
    //             'confirm' => __('Are you sure?')
    //         ]
    //     );

    //     $statuses = $this->_status->getOptionArray();

    //     $this->getMassactionBlock()->addItem(
    //         'status',
    //         [
    //             'label' => __('Change status'),
    //             'url' => $this->getUrl('crm/*/massStatus', ['_current' => true]),
    //             'additional' => [
    //                 'visibility' => [
    //                     'name' => 'status',
    //                     'type' => 'select',
    //                     'class' => 'required-entry',
    //                     'label' => __('Status'),
    //                     'values' => $statuses
    //                 ]
    //             ]
    //         ]
    //     );


    //     return $this;
    // }
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('crm/*/index', ['_current' => true]);
    }

    /**
     * @param \Ueg\Crm\Model\crmadminacl|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return FALSE;
		
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
                //echo $this->getCollection()->getSelect();
            }
        }

        return $this;
    }

}