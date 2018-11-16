<?php

namespace Ueg\Crm\Block\Adminhtml;

class Amazon extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @var string
     */
    protected $_template = 'amazon/amazon.phtml';

   
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
        
        $this->_objectId = 'amazon_id';
        $this->_controller = 'adminhtml';
        $this->_blockGroup = 'Ueg_Crm';
        
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
            $this->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Amazon\Grid', 'ueg.amazon.grid')
        );

        $addButtonProps = [
            'id' => 'gridGrid',
            'label' => __('Save'),
            'class' => 'add amazonlog',
            'button_class' => '',
            'style' => "background-color: #eb5202;border-color: #eb5202;color: #fff;"
        ];
        $this->buttonList->add('add_new', $addButtonProps);


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