define([
    'underscore',
    'orotranslation/js/translator',
    'jquery',
    'jquery.validate',
    'orowirecard/js/lib/jquery-credit-card-validator'
], function(_, __, $) {
    'use strict';

    const defaultOptions = {
        allowedCreditCards: []
    };

    return {
        validate: function(element, options) {
            options = _.extend({}, defaultOptions, options);
            const allowedCCTypes = _.values(options.allowedCreditCards);
            const validateOptions = {};

            if (allowedCCTypes.length) {
                const amexIndex = allowedCCTypes.indexOf('american_express');
                if (amexIndex !== -1) {
                    allowedCCTypes[amexIndex] = 'amex';
                }
                validateOptions.accept = allowedCCTypes;
            }

            return $(element).validateCreditCard(validateOptions).valid;
        }
    };
});
