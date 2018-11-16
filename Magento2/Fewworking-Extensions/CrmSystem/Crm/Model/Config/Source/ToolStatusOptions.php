<?php
namespace Ueg\Crm\Model\Config\Source;
class ToolStatusOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => '0', 'label' => __('Please select')],
                ['value' => 'new', 'label' => __('New')],
                ['value' => 'hold', 'label' => __('Hold')],
                ['value' => 'complete', 'label' => __('Complete')],
                ['value' => 'price', 'label' => __('Price')],
                ['value' => 'post', 'label' => __('Post')],
                ['value' => 'profile', 'label' => __('Profile')],
                ['value' => 'relist', 'label' => __('Relist')],
                ['value' => 'closed', 'label' => __('Closed')],
                ['value' => 'pending sale', 'label' => __('Pending Sale')]
            ];
        }
        return $this->_options;
    }
}
