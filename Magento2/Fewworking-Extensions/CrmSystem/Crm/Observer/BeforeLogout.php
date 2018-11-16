<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ueg\Crm\Observer;

use Magento\Framework\Event\ObserverInterface;


class BeforeLogout implements ObserverInterface
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

    private $authSession;

    private $session;

    /**
     * @param CookieFormKey $cookieFormKey
     * @param DataFormKey $dataFormKey
     */
    public function __construct(
        \Ueg\Crm\Model\DialerFactory $DialerFactory,
        \Ueg\Crm\Model\AsrFactory $AsrFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Backend\Model\Session $session
    )
    {
        $this->DialerFactory = $DialerFactory;
        $this->AsrFactory = $AsrFactory;
        $this->date = $date;
        $this->authSession = $authSession;
        $this->session = $session;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $user = $this->authSession;
        $userId = $user->getUser()->getUserId();
        $this->releaseLeads($userId);

        $this->session->unsAsr();
        $this->session->unsDialer();

        return $this;
        
    }

    private function releaseLeads($userId)
    {
        $dialerCollection = $this->DialerFactory->create()->getCollection()
                                ->addFieldToFilter('user_id', $userId)
                                ->addFieldToFilter('status', 0);
        if(count($dialerCollection) > 0) {
            foreach ($dialerCollection as $_dialer) {
                $dialer = $this->DialerFactory->create()->load($_dialer->getId());
                $dialer->setData('status', 1);
                $dialer->setData('update_at', $this->date->gmtDate());
                $dialer->save();
            }
        }

        $asrCollection = $this->AsrFactory->create()->getCollection()
                             ->addFieldToFilter('user_id', $userId)
                             ->addFieldToFilter('status', 0);
        if(count($asrCollection) > 0) {
            foreach ($asrCollection as $_asr) {
                $asr = $this->AsrFactory->create()->load($_asr->getId());
                $asr->setData('status', 1);
                $asr->setData('update_at', $this->date->gmtDate());
                $asr->save();
            }
        }
    }
}
