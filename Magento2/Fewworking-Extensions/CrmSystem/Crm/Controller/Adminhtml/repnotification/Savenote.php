<?php

namespace Ueg\Crm\Controller\Adminhtml\repnotification;

use Magento\Backend\App\Action\Context;

class Savenote extends \Magento\Backend\App\Action
{


    protected $_repnotification;


    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Ueg\Crm\Model\RepnotificationFactory $repFactory
    ) {
        $this->_repnotification = $repFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getPost('id');
        if(isset($id) && !empty($id)) {
            $note = trim($this->getRequest()->getPost('note'));
            if(isset($note) && !empty($note)) {
                $model = $this->_repnotification->create();
                $model->setData('note', $note)->setId($id)->save();
            }
        }
        echo 1;
    }
}
?>