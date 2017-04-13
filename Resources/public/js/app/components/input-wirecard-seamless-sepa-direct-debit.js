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

            mediator.on('wirecard:datastorage:initialized', this.onDataStorageInitialized, this);

            this.$el
                .on('focusout', this.options.selectors.accountOwner, $.proxy(this.onAccountOwnerFocusout, this))
                .on('focusout', this.options.selectors.iban, $.proxy(this.onIbanFocusout, this))
                .on('focusout', this.options.selectors.bic, $.proxy(this.onBicFocusout, this));
        },

        onDataStorageInitialized: function() {
            var value = this.$el.find(this.options.selectors.accountOwner).val();
            if (value) {
                this.collectAccountOwner(value);
            }
            value = this.$el.find(this.options.selectors.iban).val();
            if (value) {
                this.collectIban(value);
            }
            value = this.$el.find(this.options.selectors.bic).val();
            if (value) {
                this.collectBic(value);
            }
            this.storePaymentData();
        },

        /**
         * @param {jQuery.Event} e
         */
        onAccountOwnerFocusout: function(e) {
            if (this.collectAccountOwner(e.target.value)) {
                this.storePaymentData();
            }
        },

        /**
         * @param {jQuery.Event} e
         */
        onIbanFocusout: function(e) {
            if (this.collectIban(e.target.value)) {
                this.storePaymentData();
            }
        },

        /**
         * @param {jQuery.Event} e
         */
        onBicFocusout: function(e) {
            if (this.collectBic(e.target.value)) {
                this.storePaymentData();
            }
        },

        /**
         * @param {string} accountOwner
         * @return {boolean} true if value changed
         */
        collectAccountOwner: function(accountOwner) {
            var oldValue = this.accountOwner;
            this.accountOwner = this.validate(this.options.selectors.accountOwner) ? accountOwner : null;

            return (oldValue !== this.accountOwner);
        },

        /**
         * @param {string} iban
         * @return {boolean} true if value changed
         */
        collectIban: function(iban) {
            var oldValue = this.iban;
            this.iban = this.validate(this.options.selectors.iban) ? iban : null;

            return (oldValue !== this.iban);
        },

        /**
         * @param {string} bic
         * @return {boolean} true if value changed
         */
        collectBic: function(bic) {
            var oldValue = this.bic;
            this.bic = this.validate(this.options.selectors.bic) ? bic : null;

            return (oldValue !== this.bic);
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

            mediator.off('wirecard:datastorage:initialized', this.onDataStorageInitialized, this);

            WirecardSepaDataInputComponent.__super__.dispose.call(this);
        }
    });

    return WirecardSepaDataInputComponent;
});
