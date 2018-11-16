<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ueg\Crm\Block\Adminhtml\Customer\Edit\Tab;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Customer account form block
 */
class Appointment2 extends \Magento\Backend\Block\Template implements TabInterface {


    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
            array $data = []
    ) {

        parent::__construct($context, $data);
    }


    public function _prepareLayout() {
        $gridBlock = $this->getLayout()->createBlock(
                'Ueg\Crm\Block\Adminhtml\Appointment\Grid', 'admin.crm.appointment.grid'
        );
        $this->setChild('grid', $gridBlock);
        return parent::_prepareLayout();
    }

    public function getTabLabel(){
        return 'Calender';
    }

    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle(){
        return 'Calender';
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     * @api
     */
    public function canShowTab(){
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     * @api
     */
    public function isHidden(){
        return false;
    }

    public function isAjaxLoaded(){
        return false;
    }
    
}
