define(
[
'uiComponent',
'Magento_Checkout/js/model/payment/renderer-list'
],
function (
Component,
rendererList
) {
'use strict';
rendererList.push(
        {
                type: 'invoice30',
                component: 'Roche_PaymentOffline/js/view/payment/method-renderer/simple-method'
        }
);
        /** Add view logic here if needed */
        return Component.extend({});
}
);