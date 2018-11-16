<?php

namespace Ueg\Crm\Block\Adminhtml\Appointment;

use Ueg\Crm\Model\Appointmentoptions;
use Ueg\Crm\Helper\Data as CrmHelper;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {

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
    protected $request;

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
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Helper\Data $backendHelper, \Ueg\Crm\Model\DialerFactory $DialerFactory, \Magento\Framework\Module\Manager $moduleManager, \Magento\Backend\Model\Auth\Session $authSession, \Magento\Customer\Model\CustomerFactory $customerObject, \Magento\Authorization\Model\RoleFactory $roleObject, \Ueg\Crm\Model\AppointmentFactory $appointmentObject, Appointmentoptions $appointmentOptionInstance,
        \Magento\Framework\App\Request\Http $request, CrmHelper $crmHelper,
     array $data = []
    ) {
        $this->_dialerFactory = $DialerFactory;
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->roles = $roleObject;
        $this->customerCollection = $customerObject;
        $this->_appointmentInstance = $appointmentObject;
        $this->_appointmentOptionInstance = $appointmentOptionInstance;
        $this->request = $request;
        $this->crmHelper = $crmHelper;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        /*parent::_construct();
        $this->setId('appointmentGrid');
        $this->setDefaultSort('appointment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('postappointment_filter');*/

        parent::_construct();
        $this->setId("appointmentGrid");
        $this->setDefaultSort("appointment_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);

        if ($this->crmHelper->isCurrentUserAdministrator()) {
            $user = $this->authSession;
            $this->setDefaultFilter(array('rep_user_name' => $user->getUser()->getUsername()));
        }
    }

    public function getHeaderText() {
        return "My Meeting";
    }

    protected function getUserRole() {
        $userId = $this->authSession->getUser()->getUserId();
        $roleNameObject = $this->roles->create()->load($userId, 'user_id');
        return $roleNameObject->getRoleName();
    }

    /**
     * @return $this
     */
    protected function _prepareCollection() {

        $collection = $this->_appointmentInstance->create()->getCollection();
        $userId = $this->authSession->getUser()->getUserId();
        if (isset($userId) && !empty($userId)) {
            if (!$this->crmHelper->isCurrentUserAdministrator()) {
                $collection->addFieldToFilter( 'rep_user_id', $userId );
            }
        }
         if ($this->getCurrentControllerName()=="crmdashboard") {
                //join customer collection
                $customer_address_entity_table = $collection->getResource()->getTable('customer_address_entity');
                $joinConditions = "main_table.customer_id = $customer_address_entity_table.parent_id";

                $collection->getSelect()->join(
                        ['customer_address_entity' => $customer_address_entity_table], $joinConditions, []
                );
                 $collection->addExpressionFieldToSelect(
                        'firstname',
                        new \Zend_Db_Expr('CONCAT(customer_address_entity.firstname," ",customer_address_entity.lastname)'),
                        []
                    );
                
                 $collection->addExpressionFieldToSelect(
                        'region',
                        new \Zend_Db_Expr('customer_address_entity.region'),
                        []
                    );

                $collection->getSelect()->group('main_table.appointment_id');
        }

// echo  $collection->getSelect()->__toString(); 

        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns() {

        $appointmentOptions = $this->_appointmentOptionInstance;

        $this->addColumn('appointment_id', array(
            'header' => __('ID'),
            'index' => 'appointment_id',
            'column_css_class' => 'no-display',
            'header_css_class' => 'no-display',
        ));

        $media = $appointmentOptions->getMediaOptions();
        $mediaOptions = array();
        foreach ($media as $_media) {
            $mediaOptions[$_media] = $_media;
        }
        $this->addColumn(
                'media', array(
            'header' => __('Media'),
            'index' => 'media',
            'type' => 'options',
            'options' => $mediaOptions,
                )
        );

        // if ($this->getUserRole() == 'Administrators') {
        if ($this->crmHelper->isCurrentUserAdministrator()) {
            $this->addColumn(
                    'rep_user_name', array(
                'header' => __('Rep'),
                'index' => 'rep_user_name',
                    )
            );
        }

        if ($this->getCurrentControllerName()=="crmdashboard") {
            
                $this->addColumn(
                        'firstname', array(
                    'header' => __('Customer Name'),
                    'index' => 'firstname',
                        )
                );

                $this->addColumn(
                        'region', array(
                    'header' => __('State'),
                    'index' => 'region',
                        )
                );

        }

        $this->addColumn(
                'subject', array(
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
                'status', array(
            'header' => __('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => $statusOptions,
            'frame_callback' => array($this, 'callback_status'),
                )
        );

        $this->addColumn(
                'contact', array(
            'header' => __('Contact'),
            'index' => 'contact',
                )
        );

        $this->addColumn(
                'appointment_time', array(
            'header' => __('Appointment Time'),
            'index' => 'appointment_time',
            'type' => 'datetime',
                //'gmtoffset' => true,
                )
        );

        $this->addColumn(
                'update_at', array(
            'header' => __('Last Modified'),
            'index' => 'update_at',
            'type' => 'datetime',
                //'gmtoffset' => true,
                )
        );

        $this->addColumn(
                'modified_user_name', array(
            'header' => __('Modified By'),
            'index' => 'modified_user_name',
                )
        );

        $this->addColumn(
                'action', array(
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
    public function getGridUrl() {
        // return $this->getUrl('crm/crmdashboard/indexAppointment', ['_current' => true]);
        if ($this->getCustomId()=="crm_asr_dashboard"|| $this->getCustomId()=="crm_dialer_dashboard") {
            return $this->getUrl('crm/appointment/grid', ['_current' => true,'headertext'=>1]);
        }if ($this->getCustomId()=="crm_appointment_index") {
            return $this->getUrl('crm/appointment/grid', ['_current' => true,'headertext'=>0]);
        }
       return $this->getUrl('crm/appointment/gridajax', ['_current' => true]);
    }
    /**
     * @param \Ueg\Crm\Model\dialer|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row) {

        return 'javascript:void(0)';
    }

    public function callback_actioncolumn($value, $row, \Magento\Framework\DataObject $column, $isExport) {
        $html = '';

        $html .= '<div class="action" style="text-align: center;width:150px">';
        $html .= '<span><a class="grid-preview" href="' . $this->getUrl("crm/crmcustomer/view", array('id' => $value)) . '" target="_blank">View Account</a></span>';
        $html .= '<span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>';
        $html .= '<span><a class="grid-edit" href="javascript:void(0)" data-url="' . $this->getUrl("crm/appointment/ajaxedit", array('id' => $row->getData('appointment_id'))) . '?isAjax=true">Edit</a></span>';
        $html .= '</div>';

        return $html;
    }

    public function callback_status($value, $row, \Magento\Framework\DataObject $column, $isExport) {
        $class = "";
        $color = "";
        $appointmentTime = strtotime($row->getData('appointment_time'));
        switch ($value) {
            case "Set-up":
                $class = "yellow";
                $color = "#fff000";
                if ($appointmentTime < time()) {
                    $class = "red";
                    $color = "#ff0000";
                }
                break;
            case "Ready":
                $class = "green";
                $color = "#00ff00";
                if ($appointmentTime < time()) {
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
        $colorCss = ($color) ? "background-color: " . $color : "";

        $html .= '<div class="status ' . $class . '" style="text-align: center; ' . $colorCss . '">';
        $html .= '<span>' . $value . '</span>';
        $html .= '</div>';

        return $html;
    }

    public function getCurrentControllerName()
    {
        return $this->request->getControllerName();
    }

}
