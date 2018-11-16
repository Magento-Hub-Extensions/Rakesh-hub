<?php
namespace Ueg\Crm\Block\Adminhtml\Crmadminacl;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Ueg\Crm\Model\crmadminaclFactory
     */
    protected $_crmadminaclFactory;

    /**
     * @var \Ueg\Crm\Model\Status
     */
    protected $_status;
    protected $roleCollection;

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
        \Ueg\Crm\Model\CrmadminaclFactory $CrmadminaclFactory,
        \Ueg\Crm\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Authorization\Model\ResourceModel\Role\CollectionFactory $roleCollection,
        array $data = []
    ) {
        $this->_crmadminaclFactory = $CrmadminaclFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        $this->roleCollection = $roleCollection;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('postGrid');
        $this->setDefaultSort('role_id');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        // $collection = $this->_crmadminaclFactory->create()->getCollection();
        $collection = $this->roleCollection->create()->addFieldToFilter('role_type','G');

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
        $this->addColumn(
            'role_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'role_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'align'     => 'right',
                'width'    => '50'
            ]
        );


		
				$this->addColumn(
					'role_name',
					[
						'header' => __('Role Name'),
						'index' => 'role_name',
					]
				);
				


		
        //$this->addColumn(
            //'edit',
            //[
                //'header' => __('Edit'),
                //'type' => 'action',
                //'getter' => 'getId',
                //'actions' => [
                    //[
                        //'caption' => __('Edit'),
                        //'url' => [
                            //'base' => '*/*/edit'
                        //],
                        //'field' => 'crmacl_id'
                    //]
                //],
                //'filter' => false,
                //'sortable' => false,
                //'index' => 'stores',
                //'header_css_class' => 'col-action',
                //'column_css_class' => 'col-action'
            //]
        //);
		

		
		  // $this->addExportType($this->getUrl('crm/*/exportCsv', ['_current' => true]),__('CSV'));
		  //  $this->addExportType($this->getUrl('crm/*/exportExcel', ['_current' => true]),__('Excel XML'));

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
		
        return $this->getUrl(
            'crm/*/edit',
            ['id' => $row->getRoleId()]
        );
		
    }

	

}