/*jshint bitwise: false*/
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

            if (this.optional(element)) {
                return true;
            }

            var bic = value.replace(/ /g, '').toUpperCase();
            var patternBic = '^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$';
            var bicRegexp = new RegExp(patternBic, '');

            if (!(bicRegexp.test(bic))) {
                return false;
            }

            return true;
        },
        function(param) {
            param = _.extend({}, defaultParam, param);
            return __(param.message);
        }
    ];
});
