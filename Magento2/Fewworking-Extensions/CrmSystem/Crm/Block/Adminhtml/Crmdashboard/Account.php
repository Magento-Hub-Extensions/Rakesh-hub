<?php

namespace Ueg\Crm\Block\Adminhtml\Crmdashboard;

class Account extends \Magento\Backend\Block\Widget\Container
{
    /**
   /**
     * @var string
     */
    protected $_template = 'crmdashboard/account.phtml';


    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Widget\Context $context,array $data = [])
    {
        parent::__construct($context, $data);
    }


    protected function _construct()
    {
        
        $this->_controller = 'adminhtml_crmdashboard_account';
        $this->_blockGroup = 'ueg_crm';
        $this->_headerText = __('My Account');

        parent::_construct();
    }


    
    /**
     * Prepare   grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Crmdashboard\Account\Grid', 'ueg.crmdashboard.account.grid')
        );
        return parent::_prepareLayout();
    }
    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }
}