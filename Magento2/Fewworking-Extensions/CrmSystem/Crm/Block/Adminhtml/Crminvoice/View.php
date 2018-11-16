<?php
namespace Ueg\Crm\Block\Adminhtml\Crminvoice;

class View extends \Magento\Backend\Block\Template
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

    protected $_aclFactory;

    protected $_crmInvoiceFactory;

    protected $_invoiceItem;

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
        \Ueg\Crm\Model\CrmadminaclFactory $aclFactory,
        \Ueg\Crm\Model\CrminvoiceFactory $CrminvoiceFactory,
        \Ueg\Crm\Model\CrminvoiceitemFactory $invoiceItem,
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
        $this->_aclFactory = $aclFactory;
        $this->_crmInvoiceFactory = $CrminvoiceFactory;
        $this->_invoiceItem = $invoiceItem;
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
        return $this->_resource->getConnection( 'core_read' );
     }

     public function getResource()
     {
        return $this->_resource;
     }

     public function getRoleId()
    {
          $adminuserId = $this->_authSession->getUser()->getUserId();
          $roleNameObject = $this->roles->create()->load($adminuserId,'user_id');
          $role_id = $roleNameObject->getRoleId();
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


    public function getAclFactory()
    {
    	return $this->_aclFactory;
    }


    public function getCrmInvoice()
    {
    	return $this->_crmInvoiceFactory;
    }

    public function getCrmInvoiceItem()
    {
    	return $this->_invoiceItem;
    }




}