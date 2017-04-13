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

        /**
         * @property array
         */
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

            mediator.on('wirecard:datastorage:initialized', this.onDataStorageInitialized, this);

            this.$el
                .on('change', this.options.selectors.expirationmonth, $.proxy(this.onExpirationMonthChange, this))
                .on('change', this.options.selectors.expirationyear, $.proxy(this.onExpirationYearChange, this))
                .on('focusout', this.options.selectors.holdername, $.proxy(this.onHoldersNameFocusout, this))
                .on('focusout', this.options.selectors.pan, $.proxy(this.onPanFocusout, this))
                .on('focusout', this.options.selectors.cardVerifyCode, $.proxy(this.onCardVerifyCodeFocusout, this));
        },

        onDataStorageInitialized: function() {
            var value = this.$el.find(this.options.selectors.expirationmonth).val();
            if (value) {
                this.collectMonthDate(value);
            }
            value = this.$el.find(this.options.selectors.expirationyear).val();
            if (value) {
                this.collectYearDate(value);
            }
            value = this.$el.find(this.options.selectors.holdername).val();
            if (value) {
                this.collectHoldersName(value);
            }
            value = this.$el.find(this.options.selectors.pan).val();
            if (value) {
                this.collectPan(value);
            }
            value = this.$el.find(this.options.selectors.cardVerifyCode).val();
            if (value) {
                this.collectCardVerifyCode(value);
            }
            this.storePaymentData();
        },

        /**
         * @param {jQuery.Event} e
         */
        onExpirationMonthChange: function(e) {
            if (this.collectMonthDate(e.target.value)) {
                this.storePaymentData();
            }
        },

        /**
         * @param {string} month
         * @return {boolean} true if value changed
         */
        collectMonthDate: function(month) {
            var oldValue = this.expirationDate;
            this.month = month;
            this.setExpirationDate();
            if (!this.validateIfMonthAndYearNotBlank()) {
                this.expirationDate = null;
            }

            return (oldValue !== this.expirationDate);
        },

        /**
         * @param {jQuery.Event} e
         */
        onExpirationYearChange: function(e) {
            if (this.collectYearDate(e.target.value)) {
                this.storePaymentData();
            }
        },

        /**
         * @param {string} year
         * @return {boolean} true if value changed
         */
        collectYearDate: function(year) {
            var oldValue = this.expirationDate;
            this.year = year;
            this.setExpirationDate();
            if (!this.validateIfMonthAndYearNotBlank()) {
                this.expirationDate = null;
            }

            return (oldValue !== this.expirationDate);
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
        onHoldersNameFocusout: function(e) {
            if (this.collectHoldersName(e.target.value)) {
                this.storePaymentData();
            }
        },

        /**
         * @param {string} holdersName
         * @return {boolean} true if value changed
         */
        collectHoldersName: function(holdersName) {
            var oldValue = this.holdersName;
            this.holdersName = this.validate(this.options.selectors.holdername) ? holdersName : null;

            return (oldValue !== this.holdersName);
        },

        /**
         * @param {jQuery.Event} e
         */
        onPanFocusout: function(e) {
            if (this.collectPan(e.target.value)) {
                this.storePaymentData();
            }
        },

        /**
         * @param {string} pan
         * @return {boolean} true if value changed
         */
        collectPan: function(pan) {
            var oldValue = this.pan;
            this.pan = this.validate(this.options.selectors.pan) ? pan : null;

            return (oldValue !== this.pan);
        },

        /**
         * @param {jQuery.Event} e
         */
        onCardVerifyCodeFocusout: function(e) {
            if (this.collectCardVerifyCode(e.target.value)) {
                this.storePaymentData();
            }
        },

        /**
         * @param {string} cvc
         * @return {boolean} true if value changed
         */
        collectCardVerifyCode: function(cvc) {
            var oldValue = this.cvc;
            this.cvc = this.validate(this.options.selectors.cardVerifyCode) ? cvc : null;

            return (oldValue !== this.cvc);
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

            mediator.off('wirecard:datastorage:initialized', this.onDataStorageInitialized, this);

            WirecardCreditCardDataInputComponent.__super__.dispose.call(this);
        }
    });

    return WirecardCreditCardDataInputComponent;
});
