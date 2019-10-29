define(function(require) {
    'use strict';

    const _ = require('underscore');
    const $ = require('jquery');
    const mediator = require('oroui/js/mediator');
    const WirecardPaymentDataInputComponent = require('orowirecard/js/app/components/input-wirecard-seamless');

    const WirecardCreditCardDataInputComponent = WirecardPaymentDataInputComponent.extend({
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
        constructor: function WirecardCreditCardDataInputComponent(options) {
            WirecardCreditCardDataInputComponent.__super__.constructor.call(this, options);
        },

        /**
         * @inheritDoc
         */
        initialize: function(options) {
            this.options = _.extend({}, this.options, options);

            $.validator.loadMethod('oropayment/js/validator/credit-card-number');
            $.validator.loadMethod('oropayment/js/validator/credit-card-expiration-date');
            $.validator.loadMethod('oropayment/js/validator/credit-card-expiration-date-not-blank');

            WirecardCreditCardDataInputComponent.__super__.initialize.call(this, options);

            mediator.on('checkout:payment:before-transit', this.beforeTransit, this);

            this.$el
                .on(
                    'focusout.' + this.cid,
                    this.options.selectors.holdername,
                    this.validate.bind(this, this.options.selectors.holdername)
                )
                .on(
                    'focusout.' + this.cid,
                    this.options.selectors.pan,
                    this.validate.bind(this, this.options.selectors.pan)
                )
                .on(
                    'focusout.' + this.cid,
                    this.options.selectors.cardVerifyCode,
                    this.validate.bind(this, this.options.selectors.cardVerifyCode)
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
            const self = this;

            this.dataStorage.storeCreditCardInformation(
                {
                    pan: self.$form.find(self.options.selectors.pan).val(),
                    cardholdername: self.$form.find(self.options.selectors.holdername).val(),
                    expirationMonth: self.$form.find(self.options.selectors.expirationmonth).val(),
                    expirationYear: self.$form.find(self.options.selectors.expirationyear).val(),
                    cardverifycode: self.$form.find(self.options.selectors.cardVerifyCode).val()
                },
                function(response) {
                    const errorList = self.$form.find(self.options.wirecardErrorsSelector);
                    mediator.execute('hideLoading');
                    if (response.getStatus() === 0) {
                        errorList.html('');
                        return resumeCallback();
                    } else {
                        self.logError(response);
                        let errorOutput = '';
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

            this.$el.off('.' + this.cid);
            mediator.off('checkout:payment:before-transit', this.beforeTransit, this);
            WirecardCreditCardDataInputComponent.__super__.dispose.call(this);
        }
    });

    return WirecardCreditCardDataInputComponent;
});
