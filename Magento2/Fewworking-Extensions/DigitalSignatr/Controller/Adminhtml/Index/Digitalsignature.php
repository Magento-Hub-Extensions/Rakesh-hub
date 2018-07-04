<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DAPL\DigitalSignatr\Controller\Adminhtml\Index;
 
class Digitalsignature extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Customer compare grid
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        //echo 'hii';exit(); 
        $this->initCurrentCustomer();
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout;
    }


}