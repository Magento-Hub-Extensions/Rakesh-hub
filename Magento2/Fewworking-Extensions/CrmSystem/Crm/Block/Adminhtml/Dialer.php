<?php

namespace Ueg\Crm\Block\Adminhtml;

class Dialer extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @var string
     */
    protected $_template = 'dialer/dialer.phtml';

   
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
        
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_dialer';
        $this->_blockGroup = 'Ueg_Crm';
        
        parent::_construct();

        $this->addButton(
            'claim_leads',
            [
                'label'   => 'Claim Leads',
                'class'   => 'primary',
                'onclick' => 'setLocation(\'' . $this->getUrl('crm/dialer/dashclaim') . '\')'
            ]
        );
    }


     /**
     * Retrieve text for header element
     * 
     * @return string
     */
    public function getHeaderText()
    {
        
       return __('Dialer Lead Grid ');
       
    }

    /**
     * Prepare   grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {
        $this->_controller = 'adminhtml_dialer';
        $this->_blockGroup = 'Ueg_Crm';
        $this->_headerText = __('Dialer Lead Grid');

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Dialer\Grid', 'ueg.dialer.grid')
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