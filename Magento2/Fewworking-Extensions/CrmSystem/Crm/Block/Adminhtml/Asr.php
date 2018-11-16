<?php

namespace Ueg\Crm\Block\Adminhtml;

class Asr extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @var string
     */
    protected $_template = 'asr/asr.phtml';


    

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
        
        
        
        parent::_construct();
        
        $this->addButton(
            'claim_leads',
            [
                'label'   => 'Claim Leads',
                'class'   => 'primary',
                'onclick' => 'setLocation(\'' . $this->getUrl('crm/asr/dashclaim') . '\')'
            ]
        );

        
    }

    /**
     * Prepare button and grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {

		

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Asr\Grid', 'ueg.asr.grid')
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