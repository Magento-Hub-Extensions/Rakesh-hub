<?php
namespace Ueg\Crm\Controller\Adminhtml\crmadminacl;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;


class Save extends \Magento\Backend\App\Action
{

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $post_data = $this->getRequest()->getPostValue();

       // echo "<pre>"; print_r($this->getRequest()->getParams()); exit;
        
        try {
            if (!empty($post_data['account_overview_read'])) {
                $post_data['account_overview_read']=(in_array("all", $post_data['account_overview_read'])) ? "all" : implode(',',$post_data['account_overview_read']);
            }
            if (!empty($post_data['account_overview_write'])) {

                $post_data['account_overview_write']=(in_array("all", $post_data['account_overview_write'])) ? "all" : implode(',',$post_data['account_overview_write']);
            }

            if (!empty($post_data['inventory_read'])) {
               $post_data['inventory_read']=(in_array("all", $post_data['inventory_read'])) ? "all" : implode(',',$post_data['inventory_read']);
            }


                
        } catch (Exception $e) {
            
        }
        


        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($post_data) {

            // print_r($this->getRequest()->getParams()); exit;
             $id = $this->getRequest()->getParam('role_id');
            $model = $this->_objectManager->create('Ueg\Crm\Model\Crmadminacl');

            

           
            if ($id) {
               //  $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
               //  $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            
               //  $connection = $resource->getConnection();
               //  $tableName = $resource->getTableName('crmadminacl');

               //  $sql = "Select crmacl_id FROM " . $tableName." where role_id=$id";
               // $result = $connection->fetchAll($sql);
               // echo 'rr='.print_r($result);exit();

                $model->load($id, 'role_id');
                // print_r($model->getData());
                $model->setCreatedAt(date('Y-m-d H:i:s'));
            }

            if ($model->getId()) {
                // echo 'id= '.$model->getId();  exit;
                $post_data['crmacl_id']=$model->getId();
           }
           
	       $model->setData($post_data);

          // echo "6666666"; print_r($model->getData()); exit;

           
           

                     // echo print_r($post_data); exit;

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Crmadminacl has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    // $this->_getSession()->setFormData($post_data);
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getRoleId(), '_current' => true]);
                }
               return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                // exit($e);
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                // exit($e);
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Crmadminacl.'));
            }

            $this->_getSession()->setFormData($post_data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('role_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}