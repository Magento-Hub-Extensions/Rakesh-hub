<?php
namespace Ueg\Crm\Block\Adminhtml\Overdue;
use Ueg\Crm\Model\Appointmentoptions;
use Ueg\Crm\Helper\Data as CrmHelper;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;




    protected $_template = 'overdue/grid/extended.phtml';


    protected $authSession;

    protected $customerCollection;

    protected $_appointmentInstance;

    protected $roles;

    protected $_appointmentOptionInstance;

    protected $date;

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
        \Magento\Authorization\Model\RoleFactory $roleObject,
        \Ueg\Crm\Model\AppointmentFactory $appointmentObject,
        Appointmentoptions $appointmentOptionInstance,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        CrmHelper $crmHelper,
        array $data = []
    ) {
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->roles = $roleObject;
        $this->customerCollection = $customerObject;
        $this->_appointmentInstance = $appointmentObject;
        $this->_appointmentOptionInstance = $appointmentOptionInstance;
        $this->date = $date;
        $this->crmHelper = $crmHelper;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('overdueGrid');
        $this->setDefaultSort('appointment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('overduepost_filter');
		if ($this->crmHelper->isCurrentUserAdministrator()) {
		 $this->setDefaultFilter( array( 'rep_user_name' => $this->authSession->getUser()->getUsername() ) );
		}
    }


    public function getHeaderText()
    {
      //return "Overdue Tasks";
    }




    protected function getUserRole()
    {
        $userId = $this->authSession->getUser()->getUserId();
        $roleNameObject = $this->roles->create()->load($userId,'user_id');
        return $roleNameObject->getRoleName();
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {

    	$status = $this->_appointmentOptionInstance->getOverdueStatusOptions();

        $collection = $this->_appointmentInstance->create()->getCollection();
        $userId = $this->authSession->getUser()->getUserId();
        $collection->addFieldToFilter( 'status', array('in' => $status) );
		$collection->addFieldToFilter( 'appointment_time', array('lt' => $this->date->gmtDate()) );

		if(isset($userId) && !empty($userId)) {
			// if($this->getUserRole() != 'Ulises') {
			if (!$this->crmHelper->isCurrentUserAdministrator()) {
				$collection->addFieldToFilter( 'rep_user_id', $userId );
			}
		}


		$collection->getSelect()->join( array('custentity'=>'customer_entity'), 'main_table.customer_id = custentity.entity_id', array('custentity.default_billing'));


		$collection->getSelect()->join( array('address_entity'=>'customer_address_entity'), 'custentity.default_billing = address_entity.entity_id', array('address_entity.region'));

		//echo $collection->getSelect()->__toString();exit();
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
        
       $appointmentOptions = $this->_appointmentOptionInstance;

		// $this->addColumn('appointment_id', array(
		// 	'header'    => __('ID'),
		// 	'index'     => 'appointment_id',
		// 	'column_css_class'=>'no-display',
		// 	'header_css_class'=>'no-display',
		// ));

		$media = $appointmentOptions->getMediaOptions();
		$mediaOptions = array();
		foreach ($media as $_media) {
			$mediaOptions[$_media] = $_media;
		}
		$this->addColumn(
			'media',
			array(
				'header' => __('Media'),
				'index' => 'media',
				'type'      =>  'options',
				'options'   =>  $mediaOptions,
			)
		);

		if ($this->crmHelper->isCurrentUserAdministrator()) {
			$this->addColumn(
				'rep_user_name',
				array(
					'header' => __( 'Rep' ),
					'index'  => 'rep_user_name',
				)
			);
		}

		$this->addColumn(
			'rep_user_fullname',
			array(
				'header' => __('Customer Name'),
				'index' => 'rep_user_fullname',
			)
		);

		$this->addColumn(
			'rep_user_fullname',
			array(
				'header' => __('Customer Name'),
				'index' => 'rep_user_fullname',
			)
		);

		$this->addColumn(
			'region',
			array(
				'header' => __('State'),
				'index' => 'region',
			)
		);

		$status = $appointmentOptions->getStatusOptions();
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
				'frame_callback' => array($this, 'callback_status'),
			)
		);

		$this->addColumn(
			'contact',
			array(
				'header' => __('Contact'),
				'index' => 'contact',
			)
		);

		$this->addColumn(
			'appointment_time',
			array(
				'header' => __('Appointment Time'),
				'index' => 'appointment_time',
				'type'      => 'datetime',
				//'gmtoffset' => true,
			)
		);

		$this->addColumn(
			'update_at',
			array(
				'header' => __('Last Modified'),
				'index' => 'update_at',
				'type'      => 'datetime',
				//'gmtoffset' => true,
			)
		);

		$this->addColumn(
			'modified_user_name',
			array(
				'header' => __('Modified By'),
				'index' => 'modified_user_name',
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



		
				
				
		   //$this->addExportType($this->getUrl('crm/*/exportCsv', ['_current' => true]),__('CSV'));
		   //$this->addExportType($this->getUrl('crm/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        parent::_prepareColumns();
        return $this;
    }

	

		

      /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('crm/overdue/grid', ['_current' => true]);
    }

    /**
     * @param \Ueg\Crm\Model\dialer|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return 'javascript:void(0)';
		
    }

    public function callback_actioncolumn($value, $row, \Magento\Framework\DataObject $column, $isExport)
    {
        $html = '';

        $html .= '<div class="action" style="text-align: center;">';
        $html .= '<span><a class="grid-preview" href="'.$this->getUrl("crm/crmcustomer/view", array('id'=>$value)).'" target="_blank">View</a></span>';
        $html .= '</div>';

        return $html;
    }


    public function callback_status($value, $row, \Magento\Framework\DataObject $column, $isExport)
	{
		$class = "";
		$color = "";
		$appointmentTime = strtotime($row->getData('appointment_time'));
		switch ($value) {
			case "Set-up":
				$class = "yellow";
				$color = "#fff000";
				if($appointmentTime < time()) {
					$class = "red";
					$color = "#ff0000";
				}
				break;
			case "Ready":
				$class = "green";
				$color = "#00ff00";
				if($appointmentTime < time()) {
					$class = "red";
					$color = "#ff0000";
				}
				break;
			case "Completed":
				$class = "blue";
				$color = "#0000ff";
				break;
			case "Closed":
				$class = "purple";
				$color = "#ff00ff";
				break;
			default:
				$class = "";
				$color = "";
		}

		$html = '';
		$colorCss = ($color) ? "background-color: ". $color : "";

		$html .= '<div class="status '. $class .'" style="text-align: center; '. $colorCss .'">';
		$html .= '<span>'. $value .'</span>';
		$html .= '</div>';

		return $html;
	}

	

}