define(function(require) {
    'use strict';

    var WirecardCreditCardDataInputComponent;
    var _ = require('underscore');
    var $ = require('jquery');
    var mediator = require('oroui/js/mediator');
    var WirecardPaymentDataInputComponent = require('orowirecard/js/app/components/input-wirecard-seamless');

    WirecardCreditCardDataInputComponent = WirecardPaymentDataInputComponent.extend({
        options: _.extend({}, WirecardPaymentDataInputComponent.prototype.options, {
            selectors: {
                pan: '[data-pan]',
                holdername: '[data-card-holder-name]',
                expirationmonth: '[data-expiration-date-month]',
                expirationyear: '[data-expiration-date-year]',
                expirationDate: '[data-expiration-date]',
                cardVerifyCode: '[data-cvc]'
            }
        }),

        /**
         * @inheritDoc
         */
        constructor: function WirecardCreditCardDataInputComponent() {
            WirecardCreditCardDataInputComponent.__super__.constructor.apply(this, arguments);
        },

        /**
         * @inheritDoc
         */
        initialize: function(options) {
            this.options = _.extend({}, this.options, options);

            $.validator.loadMethod('oropayment/js/validator/credit-card-number');
            $.validator.loadMethod('oropayment/js/validator/credit-card-expiration-date');
            $.validator.loadMethod('oropayment/js/validator/credit-card-expiration-date-not-blank');

            WirecardCreditCardDataInputComponent.__super__.initialize.apply(this, arguments);

            mediator.on('checkout:payment:before-transit', this.beforeTransit, this);

            this.$el
                .on(
                    'focusout',
                    this.options.selectors.holdername,
                    $.proxy(this.validate, this, this.options.selectors.holdername)
                )
                .on(
                    'focusout',
                    this.options.selectors.pan,
                    $.proxy(this.validate, this, this.options.selectors.pan)
                )
                .on(
                    'focusout',
                    this.options.selectors.cardVerifyCode,
                    $.proxy(this.validate, this, this.options.selectors.cardVerifyCode)
                );
        },

        /**
         * @param {Object} eventData
         */
        beforeTransit: function(eventData) {
            if (eventData.data.paymentMethod !== this.options.paymentMethod || eventData.stopped) {
                return;
            }

            eventData.stopped = true;

            if (!this.validate()) {
                return;
            }

            mediator.execute('showLoading');

            this.initializeDataStorage(
                _.bind(this.dataStorageSuccess, this, eventData.resume),
                _.bind(this.dataStorageLoadFailed, this)
            );
        },

        /**
         * @param {function} resumeCallback
         */
        dataStorageSuccess: function(resumeCallback) {
            var self = this;

            this.dataStorage.storeCreditCardInformation(
                {
                    pan: self.$form.find(self.options.selectors.pan).val(),
                    cardholdername: self.$form.find(self.options.selectors.holdername).val(),
                    expirationMonth: self.$form.find(self.options.selectors.expirationmonth).val(),
                    expirationYear: self.$form.find(self.options.selectors.expirationyear).val(),
                    cardverifycode: self.$form.find(self.options.selectors.cardVerifyCode).val()
                },
                function(response) {
                    var errorList = self.$form.find(self.options.wirecardErrorsSelector);
                    mediator.execute('hideLoading');
                    if (response.getStatus() === 0) {
                        errorList.html('');
                        return resumeCallback();
                    } else {
                        self.logError(response);
                        var errorOutput = '';
                        response.getErrors().forEach(function(errorObject) {
                            errorOutput += '<li class="validation-failed">' + errorObject.consumerMessage + '</li>';
                        });
                        errorList.html(errorOutput);
                    }
                }
            );
        },

        dispose: function() {
            if (this.disposed || !this.disposable) {
                return;
            }

            this.$el.off();
            mediator.off('checkout:payment:before-transit', this.beforeTransit, this);
            WirecardCreditCardDataInputComponent.__super__.dispose.call(this);
        }
    });

    return WirecardCreditCardDataInputComponent;
});
