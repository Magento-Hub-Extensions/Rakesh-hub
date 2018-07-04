/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
var config = {
	paths:{
				flashcanvas: "DAPL_DigitalSignatr/js/digital_signature/flashcanvas",
				jqsignaturepad: "DAPL_DigitalSignatr/js/digital_signature/jquery.signaturepad",
				json2: "DAPL_DigitalSignatr/js/digital_signature/json2.min",
				signaturepad: "DAPL_DigitalSignatr/js/digital_signature/signaturepad",
		 },
		
	shim:  {

            'jqsignaturepad': {
                deps: ['jquery']
            },
            'signaturepad': {
                deps: ['jquery']
            } 
    }
};