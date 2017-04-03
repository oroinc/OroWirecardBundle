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
            },
        }),

        /**
         * @property string
         */
        month: null,

        /**
         * @property string
         */
        year: null,

        expirationDate: null,

        /**
         * @property string
         */
        holdersName: null,

        /**
         * @property string
         */
        pan: null,

        /**
         * @property string
         */
        cvc: null,

        initialize: function(options) {
            this.options = _.extend({}, this.options, options);
            WirecardCreditCardDataInputComponent.__super__.initialize.apply(this, arguments);

            $.validator.loadMethod('orowirecard/js/validator/credit-card-expiration-date');
            $.validator.loadMethod('orowirecard/js/validator/credit-card-expiration-date-not-blank');

            this.$el
                .on('change', this.options.selectors.expirationmonth, $.proxy(this.collectMonthDate, this))
                .on('change', this.options.selectors.expirationyear, $.proxy(this.collectYearDate, this))
                .on('focusout', this.options.selectors.holdername, $.proxy(this.collectHolderName, this))
                .on('focusout', this.options.selectors.pan, $.proxy(this.collectPan, this))
                .on('focusout', this.options.selectors.cardVerifyCode, $.proxy(this.collectCardVerifyCode, this));
        },

        /**
         * @param {jQuery.Event} e
         */
        collectMonthDate: function(e) {
            this.month = e.target.value;
            this.setExpirationDate();
            if (!this.validateIfMonthAndYearNotBlank()) {
                this.expirationDate = null;
            }
            this.storePaymentData();
        },

        /**
         * @param {jQuery.Event} e
         */
        collectYearDate: function(e) {
            this.year = e.target.value;
            this.setExpirationDate();
            if (!this.validateIfMonthAndYearNotBlank()) {
                this.expirationDate = null;
            }
            this.storePaymentData();
        },

        validateIfMonthAndYearNotBlank: function() {
            return this.validate(this.options.selectors.expirationDate);
        },

        setExpirationDate: function() {
            this.expirationDate = (this.month && this.year) ? {month: this.month, year: this.year} : null;
        },

        /**
         * @param {jQuery.Event} e
         */
        collectHolderName: function(e) {
            this.holdersName = this.validate(this.options.selectors.holdername) ? e.target.value : null;
            this.storePaymentData();
        },

        /**
         * @param {jQuery.Event} e
         */
        collectPan: function(e) {
            this.pan = this.validate(this.options.selectors.pan) ? e.target.value : null;
            this.storePaymentData();
        },

        /**
         * @param {jQuery.Event} e
         */
        collectCardVerifyCode: function(e) {
            this.cvc = this.validate(this.options.selectors.cardVerifyCode) ? e.target.value : null;
            this.storePaymentData();
        },

        storePaymentData: function() {
            if (this.dataStorage === null ||
                this.expirationDate === null ||
                this.holdersName === null ||
                this.pan === null ||
                this.cvc === null) {
                return;
            }

            var self = this;
            mediator.execute('showLoading');
            this.dataStorage.storeCreditCardInformation(
                {
                    pan: this.pan,
                    cardholdername: this.holdersName,
                    expirationMonth: this.expirationDate.month,
                    expirationYear: this.expirationDate.year,
                    cardverifycode: this.cvc
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

            WirecardCreditCardDataInputComponent.__super__.dispose.call(this);
        }
    });

    return WirecardCreditCardDataInputComponent;
});
