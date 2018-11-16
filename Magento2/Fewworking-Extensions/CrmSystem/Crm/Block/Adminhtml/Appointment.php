<?php

namespace Ueg\Crm\Block\Adminhtml;

class Appointment extends \Magento\Backend\Block\Widget\Container
{
    /**
   /**
     * @var string
     */
    protected $_template = 'appointment/appointment.phtml';


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
        
        $this->_controller = 'adminhtml_appointment';
        $this->_blockGroup = 'ueg_crm';
        $this->_headerText = __('My Meetings');

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
                $this->getLayout()
                ->createBlock('Ueg\Crm\Block\Adminhtml\Appointment\Grid', 'ueg.appointment.grid')
                ->setCustomId($this->getCustomId())
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