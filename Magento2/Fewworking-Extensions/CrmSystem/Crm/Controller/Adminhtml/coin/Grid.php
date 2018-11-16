<?php

namespace Ueg\Crm\Controller\Adminhtml\coin;

use Magento\Backend\App\Action;

class Grid extends \Magento\Backend\App\Action {

    /** @todo at the time when adding tabs in customer page
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $this->getResponse()->setBody(
                $this->_view->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Customer\Edit\Tab\Coin\Grid')->toHtml()
        );
    }

}
