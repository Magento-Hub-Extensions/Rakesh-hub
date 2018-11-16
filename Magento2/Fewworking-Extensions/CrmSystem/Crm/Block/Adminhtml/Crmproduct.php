<?php

namespace Ueg\Crm\Block\Adminhtml;

error_reporting(E_ALL);
ini_set("display_errors", 1);

class Crmproduct extends \Magento\Backend\Block\Template {

    protected $_crmadminaclModel;
    protected $authSession;
    protected $productFactory;
    protected $resource;
    protected  $frontUrlModel;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
            \Magento\Backend\Model\Auth\Session $authSession, 
            \Ueg\Crm\Model\CrmadminaclFactory $crmadminaclModel, 
            \Magento\Catalog\Model\ProductFactory $productFactory, 
            \Magento\Framework\App\ResourceConnection $resource,
            \Magento\Framework\Url $frontUrlModel,
            array $data = []
    ) {
        $this->authSession = $authSession;
        $this->_crmadminaclModel = $crmadminaclModel;
        $this->productFactory = $productFactory;
        $this->resource = $resource;
        $this->frontUrlModel = $frontUrlModel;
        parent::__construct($context, $data);
    }

    public function getAdminUserId() {
        return $this->authSession->getUser()->getId();
    }

    public function getRoleId() {
        $roleData = $this->authSession->getUser()->getRole()->getData('role_id');
        return $roleData;
    }

    public function getCrmaclModel() {
        $role_id = $this->getRoleId();
        $model = $this->_crmadminaclModel->create()->load($role_id, "role_id");
        return $model;
    }

    public function getLoadedProduct($id) {
        $role_id = $this->getRoleId();
        $prod = $this->productFactory->create()->load($id);
        return $prod;
    }
    
    public function getResource() {
       return $this->resource;
    }
    
    public function getProductUrl($productid, $storeCode = 'default', $categoryId = null) {
       
        return $this->frontUrlModel->getUrl('catalog/product/view', 
             ['id' => $productid, '_nosid' => true, '_query' => ['___store' => $storeCode]]);
 }

}
