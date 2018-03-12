<?php

namespace Rkdnath\Banner\Block\Index;


class Index extends \Magento\Framework\View\Element\Template {

	protected $banner;
	protected $mediaUrl;

    public function __construct(
    	\Magento\Framework\View\Element\Template\Context $context, 
    	\Rkdnath\Banner\Model\BannerFactory $banner,
    	\Magento\Store\Model\StoreManagerInterface $mediaUrl
    	) 
    {

        parent::__construct($context);
        $this->banner = $banner;
        $this->mediaUrl = $mediaUrl;

    }


    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }


    public function getBannerCollection()
    {
    	$collection = $this->banner->create()->getCollection()->addFieldToFilter( 'status', '0' );
    	return $collection;
    }

     public function getMediaUrl(){

            
            $media_dir = $this->mediaUrl->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                

            return $media_dir;
        }

}