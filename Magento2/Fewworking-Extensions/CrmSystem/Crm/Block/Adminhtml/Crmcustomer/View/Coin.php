<?php

namespace Ueg\Crm\Block\Adminhtml\Crmcustomer\View;

class Coin extends \Magento\Backend\Block\Template {

    protected $_session;
    protected $_resource;
    protected $coinoptions;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Model\Auth\Session $authSession, \Magento\Framework\App\ResourceConnection $resource, \Ueg\Crm\Model\Coinoptions $coinoptions
    ) {
        $this->_session = $authSession;
        $this->_resource = $resource;
        $this->coinoptions = $coinoptions;
        parent::__construct($context);
    }

    protected function _prepareLayout() {
        $gridBlock = $this->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Crmcustomer\View\Coin\Grid', 'coin.grid');
        $this->setChild('grid', $gridBlock);
        return parent::_prepareLayout();
    }

    function getAdminSessionModel() {
        return $this->_session;
    }

    function getCoreResource() {
        return $this->_resource;
    }

    function getCoinoptionsModel() {
        return $this->coinoptions;
    }

}
