<?php

namespace Ueg\Crm\Block\Adminhtml\Overdue;

use Ueg\Crm\Model\Appointmentoptions;

class Notification extends \Magento\Backend\Block\Widget\Grid\Extended {

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;
//    protected $_template = 'overdue/grid/extended.phtml';
    protected $authSession;
//    protected $customerCollection;
    protected $_appointmentInstance;
    protected $roles;
    protected $_appointmentOptionInstance;
    protected $crmHelper;
    protected $_session;
    protected $userFactory;
    protected $aclRetriever;

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
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Helper\Data $backendHelper, \Magento\Framework\Module\Manager $moduleManager, \Magento\Backend\Model\Auth\Session $authSession,
//            \Magento\Customer\Model\CustomerFactory $customerObject,
            \Magento\Authorization\Model\RoleFactory $roleObject, \Ueg\Crm\Model\AppointmentFactory $appointmentObject, Appointmentoptions $appointmentOptionInstance, \Ueg\Crm\Helper\Data $crmHelper, \Magento\User\Model\UserFactory $userFactory, \Magento\Authorization\Model\Acl\AclRetriever $aclRetriever, array $data = []
    ) {
        $this->moduleManager = $moduleManager;
        $this->authSession = $authSession;
        $this->roles = $roleObject;
//        $this->customerCollection = $customerObject;
        $this->_appointmentInstance = $appointmentObject;
        $this->_appointmentOptionInstance = $appointmentOptionInstance;
        $this->crmHelper = $crmHelper;
        $this->userFactory = $userFactory;
        $this->aclRetriever = $aclRetriever;

        parent::__construct($context, $backendHelper, $data);
    }

    protected function getUserRole() {
        $user = $this->authSession;
        $userId = $user->getUser()->getUserId();
        $role = $this->userFactory->create()->load($userId)->getRole();
        return $role->getData('role_name');
    }

    public function getNotificationCount() {
        $user = $this->authSession;
        $userId = $user->getUser()->getUserId();

        $appointmentOptions = $this->_appointmentOptionInstance;
        $status = $appointmentOptions->getOverdueStatusOptions();

        $collection = $this->_appointmentInstance->create()->getCollection();
        $collection->addFieldToFilter('status', array('in' => $status));
        $collection->addFieldToFilter('appointment_time', array('lt' => $this->crmHelper->now()));

        if (isset($userId) && !empty($userId)) {
            if ($this->getUserRole() != 'Administrators') {
                $collection->addFieldToFilter('rep_user_id', $userId);
            }
        }

        return $collection->count();
    }

    public function getOverdueUrl() {
        return $this->getUrl("crm/overdue");
    }

    public function getAllowedResources() {
        $user = $this->authSession->getUser();
        $role = $user->getRole();
        $resources = $this->aclRetriever->getAllowedResourcesByRole($role->getId());
        return $resources;
    }

    

}
