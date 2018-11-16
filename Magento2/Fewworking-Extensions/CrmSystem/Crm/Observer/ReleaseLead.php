<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ueg\Crm\Observer;

use Magento\Framework\Event\ObserverInterface;


class ReleaseLead implements ObserverInterface
{
    /**
     * @var CookieFormKey
     */
    private $DialerFactory;

    /**
     * @var DataFormKey
     */
    private $AsrFactory;


    private $date;


    protected $resource;

    protected $session;

    protected $customerObject;

    protected $authSession;

    /**
     * @param CookieFormKey $cookieFormKey
     * @param DataFormKey $dataFormKey
     */
    public function __construct(
        \Ueg\Crm\Model\DialerFactory $DialerFactory,
        \Ueg\Crm\Model\AsrFactory $AsrFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Backend\Model\Session $session,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Customer\Model\CustomerFactory $customerObject
    )
    {
        $this->DialerFactory = $DialerFactory;
        $this->AsrFactory = $AsrFactory;
        $this->date = $date;
        $this->resource = $resource;
        $this->session = $session;
        $this->authSession = $authSession;
        $this->customerObject = $customerObject;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

       $customerId = $observer->getEvent()->getCustomerId();
        $user = $this->authSession;
        $userId = $user->getUser()->getUserId();

        $asrsession = $this->session->getAsr();
        if(isset($asrsession) && !empty($asrsession) && is_array($asrsession)) {
            if(in_array($customerId, $asrsession)) {
                $customerModel = $this->customerObject->create()->load($customerId);
                //$customer->setData('asr_pool', 0);
                //$customer->save();

                $customerModel->getResource()->load($customerModel, $customerId);
                $customerModel->setData('asr_pool', 0)
                              ->setAttributeSetId(\Magento\Customer\Api\CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER);
                $customerModel->getResource()->save($customerModel);

                $asrCollection = $this->AsrFactory->create()->getCollection()
                                     ->addFieldToFilter('user_id', $userId)
                                     ->addFieldToFilter('customer_id', $customerId)
                                     ->addFieldToFilter('status', 0);
                if(count($asrCollection) > 0) {
                    foreach ($asrCollection as $_asr) {
                        $asr = $this->AsrFactory->create()->load($_asr->getId());
                        $asr->setData('status', 1);
                        $asr->setData('update_at', $this->date->gmtDate());
                        $asr->save();
                    }
                }

                if (($key = array_search($customerId, $asrsession)) !== false) {
                    unset($asrsession[$key]);
                }
                $this->session->setAsr($asrsession);
            }
        }

        $dialersession = $this->session->getDialer();
        if(isset($dialersession) && !empty($dialersession) && is_array($dialersession)) {
            if(in_array($customerId, $dialersession)) {
                $customerModel = $this->customerObject->create()->load($customerId);

                //$customer->setData('dialer_pool', 0);
                //$customer->save();

                $customerModel->getResource()->load($customerModel, $customerId);
                $customerModel->setData('dialer_pool', 0)
                  ->setAttributeSetId(\Magento\Customer\Api\CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER);
                $customerModel->getResource()->save($customerModel);

                

                $dialerCollection = $this->DialerFactory->create()->getCollection()
                                        ->addFieldToFilter('user_id', $userId)
                                        ->addFieldToFilter('customer_id', $customerId)
                                        ->addFieldToFilter('status', 0);
                if(count($dialerCollection) > 0) {
                    foreach ($dialerCollection as $_dialer) {
                        $dialer = $this->DialerFactory->create()->load($_dialer->getId());
                        $dialer->setData('status', 1);
                        $dialer->setData('update_at', $this->date->gmtDate());
                        $dialer->save();
                    }
                }

                if (($key = array_search($customerId, $dialersession)) !== false) {
                    unset($dialersession[$key]);
                }
                $this->session->setDialer($dialersession);
            }
        }

        return $this;
        
    }


}
