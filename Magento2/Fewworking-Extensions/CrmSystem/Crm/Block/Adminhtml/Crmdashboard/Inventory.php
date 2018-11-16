<?php

namespace Ueg\Crm\Block\Adminhtml\Crmdashboard;

class Inventory extends \Magento\Backend\Block\Widget\Container
{
    /**
   /**
     * @var string
     */
    protected $_template = 'crmdashboard/inventory.phtml';


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
        
        $this->_controller = 'adminhtml_crmdashboard_inventory';
        $this->_blockGroup = 'crm';
        $this->_headerText = __('Inventory');

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
            $this->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Crmdashboard\Inventory\Grid', 'ueg.crmdashboard.inventory.grid')
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