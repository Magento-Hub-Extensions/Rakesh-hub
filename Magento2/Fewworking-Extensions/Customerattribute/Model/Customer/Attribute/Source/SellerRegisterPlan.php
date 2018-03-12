<?php


namespace DAPL\Customerattribute\Model\Customer\Attribute\Source;

class SellerRegisterPlan extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => '0', 'label' => __('Select an option')],
                ['value' => 'standard', 'label' => __('Standard')],
                ['value' => 'advanced', 'label' => __('Advanced')],
                ['value' => 'premium', 'label' => __('Premium')]
            ];
        }
        return $this->_options;
    }
}
