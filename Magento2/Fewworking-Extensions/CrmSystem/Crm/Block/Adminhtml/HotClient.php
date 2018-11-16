<?php

namespace Ueg\Crm\Block\Adminhtml;

class HotClient extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @var string
     */
    protected $_template = 'hotclient/hotclient.phtml';

   
     /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ){
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
     }



    protected function _construct()
    {
        
        $this->_objectId = 'hotclient_id';
        $this->_controller = 'adminhtml_hotclient';
        $this->_blockGroup = 'ueg_crm';
        
        parent::_construct();

    }


     /**
     * Retrieve text for header element
     * 
     * @return string
     */
    public function getHeaderText()
    {
        
       return __('Hot Client Grid ');
       
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
            $this->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Hotclient\Grid', 'ueg.hotclient.grid')
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