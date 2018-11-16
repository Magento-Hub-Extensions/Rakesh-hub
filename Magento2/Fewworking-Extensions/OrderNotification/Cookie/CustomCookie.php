<?php
namespace Ueg\OrderNotification\Cookie;
 
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;


class CustomCookie extends AbstractHelper
{

    /**
     * Name of Cookie that holds private content version
     */
    CONST COOKIE_NAME = 'order_security_token';

    /**
     * Cookie life time
     */
    CONST COOKIE_LIFE = 604800;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @var $scopeConfigInterface
     */
    private $scopeConfigInterface;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $sessionManager;


    public function __construct(
        ScopeConfigInterface $scopeConfigInterface,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    ){
        $this->scopeConfigInterface = $scopeConfigInterface;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Get data from cookie set in remote address
     *
     * @return value
     */
    public function getCookie($name)
    {
        return $this->cookieManager->getCookie($name);
    }

    /**
     * Set data to cookie in remote address
     *
     * @param [string] $value    [value of cookie]
     * @param integer $duration [duration for cookie] 7 Days
     *
     * @return void
     */
    public function setCookie($value, $duration = 604800)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration($duration)
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());

        $this->cookieManager->setPublicCookie(self::COOKIE_NAME, $value, $metadata);

    }

    /**
     * delete cookie remote address
     *
     * @return void
     */
    public function delete($name)
    {
        $this->cookieManager->deleteCookie(
            $name,
            $this->cookieMetadataFactory
                ->createCookieMetadata()
                ->setPath($this->sessionManager->getCookiePath())
                ->setDomain($this->sessionManager->getCookieDomain())
        );
    }

    /**
     * @return var
     */
    public function getCookielifetime()
    {
        return self::COOKIE_LIFE;
    }
}