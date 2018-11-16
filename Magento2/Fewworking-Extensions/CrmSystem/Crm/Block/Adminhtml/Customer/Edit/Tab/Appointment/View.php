<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ueg\Crm\Block\Adminhtml\Customer\Edit\Tab\Appointment;

/**
 * Customer account form block
 */
class View extends \Magento\Backend\Block\Template {

    protected $appointmentFactory;
    protected $_customerRepositoryInterface;
    protected $appointmentoptionsModel;
    protected $_date;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Ueg\Crm\Model\AppointmentFactory $appointmentFactory, 
            \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface, 
            \Ueg\Crm\Model\Appointmentoptions $appointmentoptionsModel,
            array $data = []
    ) {
        $this->appointmentFactory = $appointmentFactory;
        $this->appointmentoptionsModel = $appointmentoptionsModel;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_date =  $context->getLocaleDate();
        parent::__construct($context, $data);
    }

    protected $id;

    /**
     * @param $id
     *
     * @return $this
     */
    public function setAppointmentId($id) {
        $this->id = $id;

        return $this;
    }

    public function getAppointmentData() {
        $appointment = $this->appointmentFactory->create()->load($this->id);
        return $appointment->getData();
    }

    public function getContactList() {
        $list = array();

        $appointment = $this->getAppointmentData();
        $customer = $this->_customerRepositoryInterface->getById($appointment['customer_id']);
        $list[] = $customer->getEmail();

        foreach ($customer->getAddresses() as $address) {
            if ($address->getTelephone()) {
                $list[] = $address->getTelephone();
            }
        }

        return $list;
    }

    public function getAppointmentoptionsModel() {
        return $this->appointmentoptionsModel;
    }
    
    public function getFormattedDate($date) {
        return $this->_date->date($date)->format('m/d/y H:i:s');
    }

}
