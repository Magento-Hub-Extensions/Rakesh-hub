<?php

namespace Ueg\Crm\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper {

    protected $dateTimeFactory;
    protected $date;
    protected $aclRetriever;
    protected $authSession;
    protected $timezone;
    
    public function __construct(
    \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateTimeFactory, \Magento\Framework\Stdlib\DateTime\DateTime $date, \Magento\Authorization\Model\Acl\AclRetriever $aclRetriever, 
            \Magento\Backend\Model\Auth\Session $authSession,
            \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->dateTimeFactory = $dateTimeFactory;
        $this->date = $date;
        $this->aclRetriever = $aclRetriever;
        $this->authSession = $authSession;
        $this->timezone = $timezone;
    }

    public function getGmt() {
        return $this->dateTimeFactory->create();
    }

    public function now() {
        return $this->date->gmtDate();
    }

    /*public function getAllowedResources() {
        $user = $this->authSession->getUser();
        $role = $user->getRole();
        $resources = $this->aclRetriever->getAllowedResourcesByRole($role->getId());
        return $resources;
    }*/

    /**
     * 
     * @param type $resource
     * @return type
     */
    public function isCurrentUserAdministrator($resource = 'Magento_Backend::all') {
        $role = $this->authSession->getUser()->getRole();
        $resources = $this->aclRetriever->getAllowedResourcesByRole($role->getId());
        return in_array($resource, $resources) or in_array("Magento_Backend::all", $resources);
    }
    
    public function getFormattedDate($inputdate) {
        $inputdate = date("Y-m-d",strtotime("+2 days",strtotime($inputdate)));
        $dateTimeZone = $this->timezone->date(new \DateTime($inputdate))->format('Y-m-d');
        return $dateTimeZone;
    }

}

?>