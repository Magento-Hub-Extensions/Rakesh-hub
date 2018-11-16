<?php

namespace Ueg\Crm\Block\Adminhtml\Repnotification;

use Ueg\Crm\Model\Appointmentoptions;

class Notification extends \Magento\Backend\Block\Template {

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    protected $authSession;

    protected $_appointmentInstance;
    protected $roles;
    protected $_appointmentOptionInstance;
    protected $crmHelper;
    protected $_session;
    protected $userFactory;
    protected $aclRetriever;

    protected $repFactory;

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
    \Magento\Framework\Module\Manager $moduleManager,
    \Magento\Backend\Model\Auth\Session $authSession,
    \Magento\Authorization\Model\RoleFactory $roleObject,
    \Ueg\Crm\Model\AppointmentFactory $appointmentObject,
    Appointmentoptions $appointmentOptionInstance,
    \Ueg\Crm\Helper\Data $crmHelper,
    \Magento\User\Model\UserFactory $userFactory,
    \Magento\Authorization\Model\Acl\AclRetriever $aclRetriever,
    \Ueg\Crm\Model\RepnotificationFactory $repFactory,
    array $data = []
    ) 
    {
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->roles = $roleObject;
        $this->_appointmentInstance = $appointmentObject;
        $this->_appointmentOptionInstance = $appointmentOptionInstance;
        $this->crmHelper = $crmHelper;
        $this->userFactory = $userFactory;
        $this->aclRetriever = $aclRetriever;
        $this->repFactory = $repFactory;
        parent::__construct($context, $data);
    }


    protected function getUserRole()
    {
        $user = $this->authSession;
        $userId = $user->getUser()->getUserId();
        $role = $this->userFactory->create()->load($userId)->getRole();
        return $role->getData('role_name');
    }

    public function getNotificationCount()
    {
        $collection = $this->repFactory->create()->getCollection()
                          ->addFieldToFilter('status', 0);

        $user = $this->authSession;
        $userId = $user->getUser()->getUserId();
        if(isset($userId) && !empty($userId)) {
            if($this->crmHelper->isCurrentUserAdministrator()) {
                $collection->addFieldToFilter( 'rep_user_id', $userId );
            }
        }

        return $collection->count();
    }

    public function getNotificationUrl()
    {
        return $this->getUrl("crm/repnotification/index");
    }

    

}
