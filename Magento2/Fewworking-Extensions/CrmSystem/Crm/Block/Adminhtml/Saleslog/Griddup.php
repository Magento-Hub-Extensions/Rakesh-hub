<?php
namespace Ueg\Crm\Block\Adminhtml\Saleslog;


class Griddup extends \Ueg\Crm\Block\Adminhtml\Saleslog\Grid
{


  protected function _prepareColumns()
  {

      $store = $this->_getStore();

      $this->addColumn('order_rep', array(
          'header'    => __('Sales'),
          'width'     => '50px',
          'index'     => 'order_rep',
      ));
    
      $this->addColumn('lot_number', array(
          'header'    => __('Source'),
          'width'     => '50px',
          'index'     => 'lot_number',
      ));
    
      $this->addColumn('order_date', array(
          'header'    => __('Transaction Date'),
          'width'     => '50px',
          'index'     => 'order_date',
      'type'      => 'date',
      ));
    
      $this->addColumn('invoice_number', array(
          'header'    => __('Invoice #'),
          'width'     => '50px',
          'index'     => 'invoice_number',
      ));
    
      $this->addColumn('shipping_address', array(
          'header'    => __('eBay ID & Feedback Rating'),
          'width'     => '50px',
          'index'     => 'shipping_address',
      ));
    
      $this->addColumn('last_name', array(
          'header'    => __('Last Name'),
          'width'     => '50px',
          'index'     => 'last_name',
      ));
    
      $this->addColumn('first_name', array(
          'header'    => __('1st Name'),
          'width'     => '50px',
          'index'     => 'first_name',
      ));
    
      $this->addColumn('billing_city', array(
          'header'    => __('City'),
          'width'     => '50px',
          'index'     => 'billing_city',
      ));
    
      $this->addColumn('billing_state', array(
          'header'    => __('ST'),
          'width'     => '50px',
          'index'     => 'billing_state',
      ));
    
      $this->addColumn('description', array(
          'header'    => __('Description'),
          'width'     => '50px',
          'index'     => 'description',
      ));
    
      $this->addColumn('coin_number', array(
          'header'    => __('Coin #'),
          'width'     => '50px',
          'index'     => 'coin_number',
      ));
    
      $this->addColumn('phone', array(
          'header'    => __('Number'),
          'width'     => '50px',
          'index'     => 'phone',
      ));
    
      $store = $this->_getStore();
      $this->addColumn('store_numis', array(
          'header'    => __('Store Numis Sales'),
          'width'     => '50px',
          'index'     => 'store_numis',
      'type'     => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
      ));
    
      $this->addColumn('ebay_bullion', array(
          'header'    => __('eBay Bullion Sales'),
          'width'     => '50px',
          'index'     => 'ebay_bullion',
      'type'     => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
      ));
    
      $this->addColumn('ebay_numis', array(
          'header'    => __('eBay Numis Sales'),
          'width'     => '50px',
          'index'     => 'ebay_numis',
      'type'     => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
      ));
    
      $this->addColumn('shipping', array(
          'header'    => __('Ship/Ins Charge'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'shipping',
      ));
    
      $this->addColumn('store_bullion', array(
          'header'    => __('Store Bullion'),
          'width'     => '50px',
          'index'     => 'store_bullion',
      'type'     => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
      ));
    
      $this->addColumn('qb_shipvia', array(
          'header'    => __('Ship by'),
          'width'     => '50px',
          'index'     => 'qb_shipvia',
      ));
    
      $this->addColumn('emails', array(
          'header'    => __('Email Address'),
          'width'     => '50px',
          'index'     => 'emails',
      ));
    
      $this->addColumn('payment_received', array(
          'header'    => __('Payment Received'),
          'width'     => '50px',
          'index'     => 'payment_received',
      'type'      => 'date',
      ));
    
      $this->addColumn('projected_shipdate', array(
          'header'    => __('Projected Ship Date'),
          'width'     => '50px',
          'index'     => 'projected_shipdate',
      ));
    
      $this->addColumn('qb_shipdate', array(
          'header'    => __('Date Shipped'),
          'width'     => '50px',
          'index'     => 'qb_shipdate',
      'type'      => 'date',
      ));
    
      return $this;
  }
	

}