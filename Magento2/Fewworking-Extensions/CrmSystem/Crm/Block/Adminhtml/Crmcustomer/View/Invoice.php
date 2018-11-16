<?php

namespace Ueg\Crm\Block\Adminhtml\Crmcustomer\View;

class Invoice extends \Magento\Backend\Block\Template {

    protected $_session;
    protected $_resource;
    protected $appointmentoptions;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Model\Auth\Session $authSession, \Magento\Framework\App\ResourceConnection $resource, \Ueg\Crm\Model\Appointmentoptions $appointmentoptions
    ) {
        $this->_session = $authSession;
        $this->_resource = $resource;
        $this->appointmentoptions = $appointmentoptions;
        parent::__construct($context);
    }

    function getAdminSessionModel() {
        return $this->_session;
    }

    function getCoreResource() {
        return $this->_resource;
    }

    function getAppointmentoptionsModel() {
        return $this->appointmentoptions;
    }

}
