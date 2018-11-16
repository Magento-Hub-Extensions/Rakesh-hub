<?php
namespace Ueg\Crm\Block\Adminhtml\Repnotification;
use Ueg\Crm\Model\Appointmentoptions;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;




    protected $_template = 'repnotification/grid/extended.phtml';


    protected $authSession;

    protected $customerCollection;

    protected $_appointmentInstance;

    protected $roles;

    protected $_appointmentOptionInstance;


    protected $_repnotification;


    protected $_backendHelper;

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
        \Ueg\Crm\Model\RepnotificationFactory $repFactory,
        array $data = []
    ) {
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->roles = $roleObject;
        $this->customerCollection = $customerObject;
        $this->_appointmentInstance = $appointmentObject;
        $this->_appointmentOptionInstance = $appointmentOptionInstance;
        $this->_repnotification = $repFactory;
        $this->_backendHelper = $backendHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        // parent::_construct();
        // $this->setId('repnotificationGrid');
        // $this->setDefaultSort('entity_id');
        // $this->setDefaultDir('DESC');
        // $this->setSaveParametersInSession(true);
        // $this->setUseAjax(true);
        // $this->setVarNameFilter('repnotificationpost_filter');

        parent::_construct();
        $this->setId("repnotificationGrid");
        $this->setDefaultSort("notification_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if($this->getUserRole() == 'Ulises') {
            $user = $this->authSession;
            $this->setDefaultFilter( array( 'rep_user_name' => $user->getUser()->getUsername() ) );
        }


    }


    public function getHeaderText()
    {
        //return "Rep Notification";
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

        //echo 'hii';exit();
        //echo $this->getUserRole();exit();
        $userId = $this->authSession->getUser()->getUserId();

        $collection = $this->_repnotification->create()->getCollection();
                             //->addFieldToFilter('status', 0);

        if(isset($userId) && !empty($userId)) {
            if($this->getUserRole() != 'Ulises') {
                $collection->addFieldToFilter( 'rep_user_id', $userId );
            }
        }

        $filter = $this->getParam('filter');
        $filter_data = $this->_backendHelper->prepareFilterString($filter);
        if(isset($filter_data['status']) && $filter_data['status'] == 2) {
            $collection->addFieldToFilter('status', 2);
        } else {
            $collection->addFieldToFilter('status', array('neq' => 2));
        }


        if(isset($filter_data['status']))
        {
            $collection->addFieldToFilter('status', $filter_data['status']);
        }
        if(isset($filter_data['flagstatus']))
        {
            $collection->addFieldToFilter('flagstatus', $filter_data['flagstatus']);
        }

        if(isset($filter_data['order_increment_id']))
        {
            $collection->addFieldToFilter('order_increment_id', $filter_data['order_increment_id']);
        }

        if(isset($filter_data['customer_name']))
        {
            $collection->addFieldToFilter('customer_name', $filter_data['customer_name']);
        }

        if(isset($filter_data['message']))
        {
            $collection->addFieldToFilter('message', $filter_data['message']);
        }

         if(isset($filter_data['event_created_at']['from']))
        {
            //echo '<pre>';print_r($filter_data);exit();
            $date1 = new \DateTime($filter_data['event_created_at']['from']);
            $date2 = new \DateTime($filter_data['event_created_at']['to']);
            $new_date_format1 = $date1->format('Y-m-d H:i:s');
            $new_date_format2 = $date2->format('Y-m-d H:i:s');
            $collection->addFieldToFilter('event_created_at',array('from' =>$new_date_format1 , 'to' =>$new_date_format2 ));
        }
        

        $this->setCollection($collection);
        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        
		$this->addColumn('status', array(
            'header'    => __('Status'),
            'index'     => 'status',
            'type'      =>  'options',
            'options'   =>  array(
                0 => "Unread",
                1 => "Read",
                2 => "Archive",
            ),
        ));
        $this->addColumn('flagstatus', array(
            'header'    => __('Flag'),
            'index'     => 'flagstatus',
            'type'      =>  'options',
            'name'      =>  'flagstatus',
            'options'   =>  array(
                0 => "UnFlag",
                1 => "Flag",
            ),
        ));

        $this->addColumn('event_created_at', array(
            'header'    => __('Date'),
            'index'     => 'event_created_at',
            'type'      => 'datetime',
        ));

        $this->addColumn('order_increment_id', array(
            'header'    => __('Order Number'),
            'index'     => 'order_increment_id',
        ));

        $this->addColumn('customer_name', array(
            'header'    => __('Customer Name'),
            'index'     => 'customer_name',
        ));

        $this->addColumn('message', array(
            'header'    => __('Notification'),
            'index'     => 'message',
        ));

        $this->addColumn('note', array(
            'header'    => __('Note'),
            'index'     => 'note',
            'frame_callback' => array($this, 'callback_notecolumn'),
        ));

        if($this->getUserRole() == 'Ulises') {
            $this->addColumn(
                'rep_user_name',
                array(
                    'header' => __( 'Assigned Rep' ),
                    'index'  => 'rep_user_name',
                )
            );
        }

        $this->addColumn(
            'action',
            array(
                'header' => __('Action'),
                'index' => 'notification_id',
                'frame_callback' => array($this, 'callback_actioncolumn'),
            )
        );

        $this->addExportType($this->getUrl('crm/*/exportCsv', ['_current' => true]),__('CSV'));
        $this->addExportType($this->getUrl('crm/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        parent::_prepareColumns();
        return $this;
    }

	

    public function getGridUrl()
    {
        return $this->getUrl('crm/repnotification/grid', array('_current'=> true));
    }

    public function getRowUrl($row)
    {
        return 'javascript:void(0)';
    }

    public function callback_actioncolumn($value, $row, $column, $isExport)
    {
        $html = '';
        $class = "";
        switch ($row->getStatus()) {
            case 0:
                $class .= "cunread";
                break;
            case 1:
                $class .= "cread";
                break;
            case 2:
                $class .= "carchive";
                break;
        }
        switch ($row->getFlagstatus()) {
            case 0:
                $class .= " cunflag";
                break;
            case 1:
                $class .= " cflag";
                break;
        }

        $html .= '<div class="action '. $class .'" style="text-align: center;">';
        $html .= '<span><a class="read-notification" href="'.$this->getUrl("crm/repnotification/markread", array('id'=>$value)).'">Mark Read</a></span>';
        $html .= "<span> | </span>";
        $html .= '<span><a class="flag-notification" href="'.$this->getUrl("crm/repnotification/markflag", array('id'=>$value)).'">Mark Flag</a></span>';
        $html .= '</div>';

        return $html;
    }

    public function callback_notecolumn($value, $row, $column, $isExport)
    {
        $html = '';
        $id = $row->getId();
        $html .= '<div class="action" style="text-align: center;">';
        $html .= '<textarea style="width:100%; height:50px;" name="note['.$id.']" class="textarea-box">'.$value.'</textarea>';
        $html .= '<br><input class="save_note" type="button" data-id="'.$id.'" value="Save"/>';
        $html .= '</div>';

        return $html;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('notification_id');
        $this->getMassactionBlock()->setFormFieldName('notification_ids');

        $boolean = array(
            0 => array('value' => 0, 'label' => 'Unread'),
            1 => array('value' => 1, 'label' => 'Read'),
            2 => array('value' => 2, 'label' => 'Archive'),
        );
        array_unshift($boolean, array('label'=> '', 'value'=> ''));

        $this->getMassactionBlock()->addItem('mass_status', array(
            'label'        => __('Change Status'),
            'url'          => $this->getUrl('crm/repnotification/massStatus'),
            'additional'   => array(
                'visibility'    => array(
                    'name'     => 'status',
                    'type'     => 'select',
                    'class'    => 'required-entry',
                    'label'    => __('Change Status'),
                    'values'   => $boolean
                )
            )
        ));

        $boolean2 = array(
            0 => array('value' => 0, 'label' => 'UnFlag'),
            1 => array('value' => 1, 'label' => 'Flag'),
        );
        array_unshift($boolean2, array('label'=> '', 'value'=> ''));

        $this->getMassactionBlock()->addItem('mass_flagstatus', array(
            'label'        => __('Change Flag'),
            'url'          => $this->getUrl('crm/repnotification/massFlagStatus'),
            'additional'   => array(
                'visibility'    => array(
                    'name'     => 'flagstatus',
                    'type'     => 'select',
                    'class'    => 'required-entry',
                    'label'    => __('Change Flag'),
                    'values'   => $boolean2
                )
            )
        ));

        return parent::_prepareMassaction();;
    }

    /**
     * Create encoded string for grid filter
     *
     * @param array $data
     * @return string
     */
    public function createFilterString(array $data)
    {
        array_walk_recursive($data, array($this, '_encodeFilter'));
        $query = http_build_query($data);

        return base64_encode($query);
    }

    /**
     * @param string $value
     */
    protected function _encodeFilter(&$value)
    {
        $value = trim(rawurlencode($value));
    }

	

}