define(function(require) {
    'use strict';

    const _ = require('underscore');
    const $ = require('jquery');
    const mediator = require('oroui/js/mediator');
    const WirecardPaymentDataInputComponent = require('orowirecard/js/app/components/input-wirecard-seamless');

    const WirecardSepaDataInputComponent = WirecardPaymentDataInputComponent.extend({
        options: _.extend({}, WirecardPaymentDataInputComponent.prototype.options, {
            selectors: {
                accountOwner: '[data-account-owner]',
                iban: '[data-bank-iban]',
                bic: '[data-bank-bic]'
            }
        }),

        /**
         * @inheritDoc
         */
        constructor: function WirecardSepaDataInputComponent(options) {
            WirecardSepaDataInputComponent.__super__.constructor.call(this, options);
        },

        /**
         * @inheritDoc
         */
        initialize: function(options) {
            this.options = _.extend({}, this.options, options);

            $.validator.loadMethod('orowirecard/js/validator/sepa-iban');
            $.validator.loadMethod('orowirecard/js/validator/sepa-bic');

            WirecardSepaDataInputComponent.__super__.initialize.call(this, options);

            mediator.on('checkout:payment:before-transit', this.beforeTransit, this);

            this.$el
                .on(
                    'focusout.' + this.cid,
                    this.options.selectors.accountOwner,
                    this.validate.bind(this, this.options.selectors.accountOwner)
                )
                .on(
                    'focusout.' + this.cid,
                    this.options.selectors.iban,
                    this.validate.bind(this, this.options.selectors.iban)
                )
                .on(
                    'focusout.' + this.cid,
                    this.options.selectors.bic,
                    this.validate.bind(this, this.options.selectors.bic)
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

            this.dataStorage.storeSepaDdInformation(
                {
                    accountOwner: self.$form.find(self.options.selectors.accountOwner).val(),
                    bankAccountIban: self.$form.find(self.options.selectors.iban).val(),
                    bankBic: self.$form.find(self.options.selectors.bic).val()
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
            WirecardSepaDataInputComponent.__super__.dispose.call(this);
        }
    });

    return WirecardSepaDataInputComponent;
});
