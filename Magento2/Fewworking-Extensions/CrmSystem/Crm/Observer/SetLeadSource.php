<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ueg\Crm\Observer;

use Magento\Framework\Event\ObserverInterface;


class SetLeadSource implements ObserverInterface
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


    protected $request;

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
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->DialerFactory = $DialerFactory;
        $this->AsrFactory = $AsrFactory;
        $this->date = $date;
        $this->resource = $resource;
        $this->session = $session;

        $this->request = $request;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

       $params = $this->request->getParams();
        if(isset($params['asr']) && $params['asr'] == 'true') {
            $asr = $this->session->getAsr();
            if(isset($asr) && !empty($asr) && is_array($asr)) {
                if(!in_array($params['id'], $asr)) {
                    $asr[] = $params['id'];
                }
            } else {
                $asr = array( $params['id'] );
            }
            $this->session->setAsr($asr);
        }
        if(isset($params['dialer']) && $params['dialer'] == 'true') {
            $dialer = $this->session->getDialer();
            if(isset($dialer) && !empty($dialer) && is_array($dialer)) {
                if(!in_array($params['id'], $dialer)) {
                    $dialer[] = $params['id'];
                }
            } else {
                $dialer = array( $params['id'] );
            }
            $this->session->setDialer($dialer);
        }

        return $this;
        
    }


}
