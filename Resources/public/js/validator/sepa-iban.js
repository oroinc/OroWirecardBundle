define([
    'underscore',
    'orotranslation/js/translator',
    'jquery',
    'jquery.validate',
    'jquery.validate-additional-methods'
], function(_, __, $) {
    'use strict';

    const defaultParam = {
        message: 'oro.wirecard.validation.iban'
    };

    /**
     * @export orowirecard/js/validator/sepa-iban
     */
    return [
        'sepa-iban',
        function(...args) {
            return $.validator.methods.iban.apply(this, args);
        },
        function(param) {
            param = _.extend({}, defaultParam, param);
            return __(param.message);
        }
    ];
});
