define(
[
'Magento_Checkout/js/view/payment/default'
],
function (Component) {
'use strict';
return Component.extend({
    defaults: {
    template: 'Roche_PaymentOffline/payment/simple'
},
/** Returns send check to info */
getMailingAddress: function() {
    return window.checkoutConfig.payment.invoice30.mailingAddress;
}

});
}
);