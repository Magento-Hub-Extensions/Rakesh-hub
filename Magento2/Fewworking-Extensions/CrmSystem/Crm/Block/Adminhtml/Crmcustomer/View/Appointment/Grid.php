<?php
namespace Ueg\Crm\Block\Adminhtml\Crmcustomer\View\Appointment;

use Ueg\Crm\Model\Appointmentoptions;
use Ueg\Crm\Helper\Data as CrmHelper;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Ueg\Crm\Model\dialerFactory
     */
    protected $_dialerFactory;


    protected $_template = 'crmdashboard/grid/extended.phtml';


    protected $authSession;

    protected $customerCollection;

    protected $_appointmentInstance;

    protected $roles;

    protected $_appointmentOptionInstance;

    protected $crmHelper;

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
        \Ueg\Crm\Model\DialerFactory $DialerFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Customer\Model\CustomerFactory $customerObject,
        \Magento\Authorization\Model\RoleFactory $roleObject,
        \Ueg\Crm\Model\AppointmentFactory $appointmentObject,
        Appointmentoptions $appointmentOptionInstance,
	    CrmHelper $crmHelper,
        array $data = []
    ) {
        $this->_dialerFactory = $DialerFactory;
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->roles = $roleObject;
        $this->customerCollection = $customerObject;
        $this->_appointmentInstance = $appointmentObject;
        $this->_appointmentOptionInstance = $appointmentOptionInstance;
	    $this->crmHelper = $crmHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
       parent::_construct();
        $this->setId('appointmentGrid');
        $this->setDefaultSort('appointment_time');
        $this->setDefaultDir('asc');

        $this->setUseAjax(true);

	    if ($this->crmHelper->isCurrentUserAdministrator()) {
		    $user = $this->authSession;
		    $this->setDefaultFilter(array('rep_user_name' => $user->getUser()->getUsername()));
	    }
    }



    public function getHeaderText()
    {
      //return "My Meeting";
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

        $collection = $this->_appointmentInstance->create()->getCollection();

	    $userId = $this->authSession->getUser()->getUserId();
	    if (isset($userId) && !empty($userId)) {
		    if (!$this->crmHelper->isCurrentUserAdministrator()) {
			    $collection->addFieldToFilter( 'rep_user_id', $userId );
		    }
	    }

        $customerId = $this->getRequest()->getParam('id');
		if(isset($customerId) && !empty($customerId)) {
			$collection->addFieldToFilter('customer_id', $customerId);
		}

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
			'subject',
			array(
				'header' => __('Subject'),
				'index' => 'subject',
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
				'index' => 'appointment_id',
				'filter' => false,
				'sortable' => false,
				'frame_callback' => array($this, 'callback_actioncolumn'),
			)
		);

		return parent::_prepareColumns();
    }

	

		

      /**
     * @return string
     */
    public function getGridUrl()
    {
       return $this->getUrl('crm/appointment/grid', array('_current' => true, 'id' => $this->getRequest()->getParam('id')));
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
        $html .= '<span><a class="grid-preview" href="' . $this->getUrl("crm/crmcustomer/view", array('id' => $value)) . '" target="_blank">View</a></span>';
        $html .= '<span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>';
        $html .= '<span><a class="grid-edit" href="javascript:void(0)" data-url="' . $this->getUrl("crm/appointment/ajaxedit", array('id' => $row->getData('appointment_id'))) . '?isAjax=true">Edit</a></span>';
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