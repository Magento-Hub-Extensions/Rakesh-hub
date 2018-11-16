<?php
namespace Ueg\OrderNotification\Model\Config\Source;

class Position implements \Magento\Framework\Option\ArrayInterface
{
 public function toOptionArray()
 {
  return [
    ['value' => 'bl', 'label' => __('Bottom-Left')],
    ['value' => 'br', 'label' => __('Bottom-Right')],
    ['value' => 'bc', 'label' => __('Bottom-Center')],
    ['value' => 'tl', 'label' => __('Top-Left')],
    ['value' => 'tr', 'label' => __('Top-Right')],
    ['value' => 'tc', 'label' => __('Top-Center')]
  ];
 }
}