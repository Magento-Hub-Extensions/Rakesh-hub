<?php

namespace Ueg\OrderNotification\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper {
    
    public function __construct(\Magento\Framework\App\Helper\Context $context)
    {
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }


    public function getConfig($config_path){
            return $this->scopeConfig->getValue(
                $config_path,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
         }


    public function getOrderNotificationStatus()
    {
        $ordernotification_enable = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_enable');
        return $ordernotification_enable; 
    }

     public function isForVirtual()
    {
        $ordernotification_virtual_product = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_virtual_product');
        return $ordernotification_virtual_product;  
    }

     public function isForResponsive()
    {
        $ordernotification_enable_mobile = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_enable_mobile');
        return $ordernotification_enable_mobile;  
    }

     public function getNotifyPosition()
    {
        $ordernotification_position = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_position');
        return $ordernotification_position;  
    }

     public function getMssgTemplate()
    {
        $ordernotification_msg_template = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_msg_template');
        return $ordernotification_msg_template;  
    }


    public function getTimeDelay()
    {
        $ordernotification_time_delay = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_time_delay');
        return $ordernotification_time_delay;  
    }


    public function getFirstPageLoad()
    {
        $ordernotification_first_page_load = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_first_page_load');
        return $ordernotification_first_page_load;  
    }

    public function getReappearAfterClose()
    {
        $ordernotification_reappear_after_close = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_reappear_after_close');
        return $ordernotification_reappear_after_close;  
    }

    public function getOrderStatus()
    {
        $ordernotification_select_order_status = $this->getConfig('order_notification_section/ordernotification_general/ordernotification_select_order_status');
        return $ordernotification_select_order_status;  
    }

    public function getBcGroundColor()
    {
        $ordernotification_background_colour = $this->getConfig('order_notification_section/ordernotification_set_colors/ordernotification_background_colour');
        return $ordernotification_background_colour;  
    }

    public function getBorderColor()
    {
        $ordernotification_border_colour = $this->getConfig('order_notification_section/ordernotification_set_colors/ordernotification_border_colour');
        return $ordernotification_border_colour;  
    }

    public function getTextColor()
    {
        $ordernotification_text_colour = $this->getConfig('order_notification_section/ordernotification_set_colors/ordernotification_text_colour');
        return $ordernotification_text_colour;  
    }

    public function getLinkColor()
    {
        $ordernotification_link_colour = $this->getConfig('order_notification_section/ordernotification_set_colors/ordernotification_link_colour');
        return $ordernotification_link_colour;  
    }

     public function getLinkHoverColor()
    {
        $ordernotification_link_hover_colour = $this->getConfig('order_notification_section/ordernotification_set_colors/ordernotification_link_hover_colour');
        return $ordernotification_link_hover_colour;  
    }


}

?>