<?php
namespace Ueg\Crm\Model\Customer\Attribute\Source;
class Customeroptions15242422065 extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{




    protected function getAdminUserList()
    {
        $list = array();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $userFactory = $objectManager->create('Magento\User\Model\UserFactory');

        $users = $userFactory->create()->getCollection();
        if(count($users)) {
            foreach ($users as $_user) {
                $id = $_user->getUserId();
                $list[$id] = $_user->getUsername();
            }
        }

        return $list;
    }

    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = array(
            
                array(
                    "label" => __("Personal Check"),
                    "value" =>  1
                ),
    
                array(
                    "label" => __("Bank Check"),
                    "value" =>  2
                ),
    
                array(
                    "label" => __("Cashiers Check"),
                    "value" =>  3
                ),
    
                array(
                    "label" => __("Bank Wire"),
                    "value" =>  4
                ),
    
                array(
                    "label" => __("PayPal"),
                    "value" =>  5
                ),
    
                array(
                    "label" => __("Credit Card"),
                    "value" =>  6
                ),
    
                array(
                    "label" => __("Cash"),
                    "value" =>  7
                ),
    
            );
        }
        return $this->_options;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $_options = array();
        foreach ($this->getAllOptions() as $option) {
            $_options[$option["value"]] = $option["label"];
        }
        return $_options;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions();
        foreach ($options as $option) {
            if ($option["value"] == $value) {
                return $option["label"];
            }
        }
        return false;
    }

    /**
     * Retrieve Column(s) for Flat
     *
     * @return array
     */
    public function getFlatColums()
    {
        $columns = array();
        $columns[$this->getAttribute()->getAttributeCode()] = array(
            "type"      => "tinyint(1)",
            "unsigned"  => false,
            "is_null"   => true,
            "default"   => null,
            "extra"     => null
        );

        return $columns;
    }

    /**
     * Retrieve Indexes(s) for Flat
     *
     * @return array
     */
    public function getFlatIndexes()
    {
        $indexes = array();

        $index = "IDX_" . strtoupper($this->getAttribute()->getAttributeCode());
        $indexes[$index] = array(
            "type"      => "index",
            "fields"    => array($this->getAttribute()->getAttributeCode())
        );

        return $indexes;
    }

    /**
     * Retrieve Select For Flat Attribute update
     *
     * @param int $store
     * @return Varien_Db_Select|null
     */
    public function getFlatUpdateSelect($store)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $AttributeUpdate = $objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute');
        return $AttributeUpdate->create()->getFlatUpdateSelect($this->getAttribute(), $store);
    }
}
