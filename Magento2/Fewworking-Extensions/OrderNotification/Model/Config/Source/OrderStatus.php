<?php
namespace Ueg\OrderNotification\Model\Config\Source;

class OrderStatus implements \Magento\Framework\Option\ArrayInterface
{
 public function toOptionArray()
 {
  return [
    ['value' => 'completed_order', 'label' => __('Completed Order')],
    ['value' => 'any_order', 'label' => __('Any Order')]
  ];
 }
}