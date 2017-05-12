define([
    'underscore',
    'orotranslation/js/translator'
], function(_, __) {
    'use strict';

    var defaultParam = {
        message: 'oro.wirecard.validation.bic'
    };

    /**
     * @export orowirecard/js/validator/sepa-bic
     */
    return [
        'sepa-bic',
        function(value, element) {
            var bic = value.replace(/ /g, '').toUpperCase();
            var patternBic = '^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$';
            var bicRegexp = new RegExp(patternBic, '');

            return bicRegexp.test(bic);
        },
        function(param) {
            param = _.extend({}, defaultParam, param);
            return __(param.message);
        }
    ];
});
