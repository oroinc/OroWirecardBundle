define(function(require) {
    'use strict';

    var WirecardSepaDataInputComponent;
    var _ = require('underscore');
    var $ = require('jquery');
    var mediator = require('oroui/js/mediator');
    var WirecardPaymentDataInputComponent = require('orowirecard/js/app/components/input-wirecard-seamless');

    WirecardSepaDataInputComponent = WirecardPaymentDataInputComponent.extend({

        options: _.extend({}, WirecardPaymentDataInputComponent.prototype.options, {
            selectors: {
                accountOwner: '[data-account-owner]',
                iban: '[data-bank-iban]',
                bic: '[data-bank-bic]',
            },
        }),

        /**
         * @property string
         */
        accountOwner: null,

        /**
         * @property string
         */
        iban: null,

        /**
         * @property string
         */
        bic: null,

        initialize: function(options) {
            this.options = _.extend({}, this.options, options);
            WirecardSepaDataInputComponent.__super__.initialize.apply(this, arguments);

            $.validator.loadMethod('orowirecard/js/validator/sepa-iban');
            $.validator.loadMethod('orowirecard/js/validator/sepa-bic');

            this.$el
                .on('focusout', this.options.selectors.accountOwner, $.proxy(this.collectAccountOwner, this))
                .on('focusout', this.options.selectors.iban, $.proxy(this.collectIban, this))
                .on('focusout', this.options.selectors.bic, $.proxy(this.collectBic, this));
        },

        /**
         * @param {jQuery.Event} e
         */
        collectAccountOwner: function(e) {
            this.accountOwner = this.validate(this.options.selectors.accountOwner) ? e.target.value : null;
            this.storePaymentData();
        },

        /**
         * @param {jQuery.Event} e
         */
        collectIban: function(e) {
            this.iban = this.validate(this.options.selectors.iban) ? e.target.value : null;
            this.storePaymentData();
        },

        /**
         * @param {jQuery.Event} e
         */
        collectBic: function(e) {
            this.bic = this.validate(this.options.selectors.bic) ? e.target.value : null;
            this.storePaymentData();
        },

        storePaymentData: function() {
            if (this.dataStorage === null ||
                this.accountOwner === null ||
                this.iban === null ||
                this.bic === null) {
                return;
            }

            var self = this;
            mediator.execute('showLoading');
            this.dataStorage.storeSepaDdInformation(
                {
                    accountOwner: this.accountOwner,
                    bankAccountIban: this.iban,
                    bankBic: this.bic
                },
                function(response) {
                    self.handleStorageResponse.call(self, response);
                    mediator.execute('hideLoading');
                }
            );
        },

        dispose: function() {
            if (this.disposed) {
                return;
            }

            this.$el.off();

            WirecardSepaDataInputComponent.__super__.dispose.call(this);
        }
    });

    return WirecardSepaDataInputComponent;
});
