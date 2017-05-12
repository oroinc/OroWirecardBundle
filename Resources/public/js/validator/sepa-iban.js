define([
    'underscore',
    'orotranslation/js/translator',
    'jquery',
    'jquery.validate',
    'jquery.validate-additional-methods'
], function(_, __, $) {
    'use strict';

    var defaultParam = {
        message: 'oro.wirecard.validation.iban'
    };

    /**
     * @export orowirecard/js/validator/sepa-iban
     */
    return [
        'sepa-iban',
        function() {
            return $.validator.methods.iban.apply(this, arguments);
        },
        function(param) {
            param = _.extend({}, defaultParam, param);
            return __(param.message);
        }
    ];
});
