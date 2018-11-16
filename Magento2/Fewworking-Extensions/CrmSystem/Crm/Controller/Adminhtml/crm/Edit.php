<?php

namespace Ueg\Crm\Controller\Adminhtml\crmadminacl;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $roleCollection;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Authorization\Model\ResourceModel\Role\CollectionFactory $roleCollection
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
         $this->roleCollection = $roleCollection;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ueg_Crm::Crmadminacl')
            ->addBreadcrumb(__('Ueg Crm'), __('Ueg Crm'))
            ->addBreadcrumb(__('Manage Item'), __('Manage Item'));
        return $resultPage;
    }

    /**
     * Edit Item
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $params=$this->getRequest()->getParams();

        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Ueg\Crm\Model\Crmadminacl');

        // 2. Initial checking
        if (!empty($id)) {
            $model->load($id,'role_id');
            
        }
        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        
        if (!empty($data)) {
            $model->setData($data);
        }
        $model->setData('role_id',$id);
        // 4. Register model to use later in blocks
        $this->_coreRegistry->register('crmadminacl', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(__('Ueg'), __('Ueg'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Item') : __('New Item'),
            $id ? __('Edit Item') : __('New Item')
        );
        $collection = $this->roleCollection->create();
       $rolename= $collection->addFieldToFilter('role_id',['eq'=>$id])
                    ->addFieldToSelect('role_name')
                    ->getFirstItem()
                    ->getRoleName();
        
        $resultPage->getConfig()->getTitle()->prepend($id ? __("Edit Role '").$rolename."'" : __('New Item'));
        //$resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Item'));

        return $resultPage;
    }
}