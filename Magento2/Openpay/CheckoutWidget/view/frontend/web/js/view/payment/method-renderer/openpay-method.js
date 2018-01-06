/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Openpay_CheckoutWidget/payment/openpay'
            },
            placeOrder: function (data, event) {
                var self = this;

                if (event) {
                    event.preventDefault();
                }

                if (this.validate()) {
                    this.isPlaceOrderActionAllowed(false);

                    this.getPlaceOrderDeferredObject()
                        .fail(
                            function () {
                                self.isPlaceOrderActionAllowed(true);
                            }
                        ).done(
                            function () {
                                if (self.redirectAfterPlaceOrder) {
                                    if (!location.origin) location.origin = location.protocol + "//" + location.host;
                                    //window.location.replace(location.origin + '/openpay/handoverurl/index');
                                    window.location.replace(window.authenticationPopup.baseUrl + 'openpay/handoverurl/index');
                                    return false;
                                }
                            }
                        );

                    return true;
                }

                return false;
            },
            /** Returns payment method instructions */
            getInstructions: function() {
                console.log(window.checkoutConfig);
                return window.checkoutConfig.payment.instructions[this.item.method];
            },
           getBlock: function() {
                              if(this.item.method == 'openpay'){
                                  return window.checkoutConfig.mynewconfig;
                               }
                               else{
                                        return false;
                               }
            },
            
            
            openimagePath:function() {
                              if(this.item.method == 'openpay'){
                                   return window.checkoutConfig.imageopenpay;
                               }
                               else{
                                        return false;
                               }
            },
            
            
            displaysection:function() {
                              if(window.checkoutConfig.imageopenpay){
                                   return true;
                               }
                               else{
                                        return false;
                               }
            }
            
            
            
            
        });
    }
);
