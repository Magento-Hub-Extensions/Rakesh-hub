<?php

namespace Ueg\Crm\Block\Adminhtml;

class Account extends \Magento\Backend\Block\Widget\Container
{
    /**
   /**
     * @var string
     */
    protected $_template = 'account/account.phtml';


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
        
        $this->_controller = 'adminhtml_account';
        $this->_blockGroup = 'Ueg_Crm';
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
            $this->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Account\Grid', 'ueg.account.grid')
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