<?php
namespace Ueg\Crm\Block\Adminhtml;

class Crminvoice extends \Magento\Backend\Block\Template
{
    
    protected $_asrOptions;

    protected $_dialerOptions;

    protected $_authSession;

    protected $_eavConfig;

    protected $date;

    protected $roles;

    protected $_resource;

    protected $_appointmentoptions;

    protected $_backendSession;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerAsrOptions $asrOptions,
        \Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerDialerOptions $dialerOptions,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Authorization\Model\RoleFactory $roleObject,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\ResourceConnection $resource,
        \Ueg\Crm\Model\Appointmentoptions $Appointmentoptions,
        array $data = []
    ){
        $this->_asrOptions = $asrOptions;
        $this->_dialerOptions = $dialerOptions;
        $this->_authSession = $authSession;
        $this->_eavConfig = $eavConfig;
        $this->date = $date;
        $this->roles = $roleObject;
        $this->_resource = $resource;
        $this->_appointmentoptions = $Appointmentoptions;
        $this->_backendSession = $context->getBackendSession();
        parent::__construct($context, $data);
     }

     public function getAsrOption()
     {
        return $this->_asrOptions->getOptionArray();
     }

     public function getDialerOption()
     {
        return $this->_dialerOptions->getOptionArray();
     }

     public function getEavAttr()
     {
        return $this->_eavConfig;
     }

     public function getauthSession()
     {
        return $this->_authSession;
     }

     public function getReadConnection()
     {
        return $this->_resource->getConnection();
     }

     public function getResource()
     {
        return $this->_resource;
     }

     public function getRoleId()
    {
          $adminuserId = $this->_authSession->getUser()->getUserId();
          $roleNameObject = $this->roles->create()->getCollection()->addFieldToFilter('user_id',$adminuserId);

          $role_id = array();
          foreach ($roleNameObject as $key => $_roleNameObject) {
              $role_id[] = $_roleNameObject->getRoleId();
          }
         
          return $role_id;
    }

    public function getGmtDate()
    {
        return $this->date;
    }

    public function getAdminUserList()
    {
        return $this->_appointmentoptions->getAdminUserList();
    }

    public function getBackendSession()
    {
        return $this->_backendSession;
    }

}