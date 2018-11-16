<?php
namespace Ueg\Crm\Model\Config\Source;
class PriceMethodOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => 'fixed', 'label' => __('Fixed')],
                ['value' => 'spot$', 'label' => __('Spot + $')],
                ['value' => 'spot%', 'label' => __('Spot + %')],
                ['value' => 'generic', 'label' => __('Generic')]
            ];
        }
        return $this->_options;
    }
}
