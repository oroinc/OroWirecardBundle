define(function(require) {
    'use strict';

    var WirecardPaymentDataInputComponent;
    var _ = require('underscore');
    var $ = require('jquery');
    var mediator = require('oroui/js/mediator');
    var routing = require('routing');
    var BaseComponent = require('oroui/js/app/components/base/component');
    require('jquery.validate');

    WirecardPaymentDataInputComponent = BaseComponent.extend({
        options: {
            hasInputForm: false,
            paymentMethod: null,
            validationSelector: '[data-validation]',
            initiatePaymentMethodRoute: null,
            progressButtonSelector: 'button.checkout__form__submit[type="submit"]',
            formSelector: '[data-wirecard-form]',
            wirecardErrorsSelector: '[data-wirecard-errors]'
        },

        dataStorage: null,

        initializingDataStorage: false,

        lastDataStorageStatus: null,

        /**
         * @property {jQuery}
         */
        $el: null,

        initialize: function(options) {
            this.options = _.extend({}, this.options, options);

            this.$el = this.options._sourceElement;
            this.$form = this.$el.find(this.options.formSelector);

            mediator.on('checkout:payment:before-transit', this.beforeTransit, this);
            mediator.on('checkout:payment:method:changed', this.onPaymentMethodChanged, this);
        },

        handleStorageResponse: function(storageResponse) {
            this.lastDataStorageStatus = storageResponse.getStatus();
            var errorList = this.$form.find(this.options.wirecardErrorsSelector);
            var errorOutput = '';
            if (this.lastDataStorageStatus !== 0) {
                storageResponse.getErrors().forEach(function(errorObject) {
                    errorOutput += '<li class="validation-failed">' + errorObject.consumerMessage + '</li>';
                });
            }
            errorList.html(errorOutput);
            //errorList.toggleClass('validation-failed', this.lastDataStorageStatus === 0);
        },

        /**
        * @param {Object} eventData
        */
        beforeTransit: function(eventData) {
            if (this.$form.length > 0 && eventData.data.paymentMethod === this.options.paymentMethod) {
                var validationStatus = this.validate(null) && (this.lastDataStorageStatus === 0);
                eventData.stopped = !validationStatus;
            }
        },

        refreshPaymentMethod: function() {
            mediator.trigger('checkout:payment:method:refresh');
        },

        dispose: function() {
            if (this.disposed) {
                return;
            }

            this.$el.off();

            mediator.off('checkout:payment:method:changed', this.onPaymentMethodChanged, this);
            mediator.off('checkout:payment:before-transit', this.beforeTransit, this);

            WirecardPaymentDataInputComponent.__super__.dispose.call(this);
        },

        /**
         * @param {String} elementSelector
         */
        validate: function(elementSelector) {
            var virtualForm = $('<form>');

            var appendElement;
            if (elementSelector) {
                appendElement = this.$form.find(elementSelector).clone();
            } else {
                appendElement = this.$form.clone();
            }

            virtualForm.append(appendElement);

            var self = this;
            var validator = virtualForm.validate({
                ignore: '', // required to validate all fields in virtual form
                errorPlacement: function(error, element) {
                    var $el = self.$form.find('#' + $(element).attr('id'));
                    var parentWithValidation = $el.parents(self.options.validationSelector);

                    $el.addClass('error');

                    if (parentWithValidation.length) {
                        error.appendTo(parentWithValidation.first());
                    } else {
                        error.appendTo($el.parent());
                    }
                }
            });

            virtualForm.find('select').each(function(index, item) {
                //set new select to value of old select
                //http://stackoverflow.com/questions/742810/clone-isnt-cloning-select-values
                $(item).val(self.$form.find('select').eq(index).val());
            });

            // Add validator to form
            $.data(virtualForm, 'validator', validator);

            var errors;

            if (elementSelector) {
                errors = this.$form.find(elementSelector).parent();
            } else {
                errors = this.$form;
            }

            errors.find(validator.settings.errorElement + '.' + validator.settings.errorClass).remove();
            errors.parent().find('.error').removeClass('error');

            return validator.form();

        },

        /**
         * @param {Object} eventData
         */
        onPaymentMethodChanged: function(eventData) {
            if (eventData.paymentMethod === this.options.paymentMethod) {
                this.initializeDataStorage();
            }
        },

        initializeDataStorage: function() {
            var self = this;
            if (!this.initializingDataStorage && this.options.initiatePaymentMethodRoute && !this.dataStorage) {
                this.initializingDataStorage = true;
                mediator.execute('showLoading');
                $.ajax({
                    url: routing.generate(
                        this.options.initiatePaymentMethodRoute,
                        {
                            id: this.options.sourceEntityId,
                            paymentMethod: this.options.paymentMethod,
                        }
                    ),
                    type: 'POST',
                }).done(function(data) {
                    var errN = data.errors && parseInt(data.errors);
                    if (errN) {
                        for (var i = 0; i < errN; i++) {
                            mediator.execute(
                                'showErrorMessage',
                                data.error[i + 1].consumerMessage,
                                data.error[i + 1].errorCode + ':' + data.error[i + 1].message
                            );
                        }
                    } else {
                        require([data.javascriptUrl], function() {
                            // jscs:disable requireCamelCaseOrUpperCaseIdentifiers
                            // jshint -W117
                            self.dataStorage = new WirecardCEE_DataStorage();
                            // jshint +W117
                            // jscs:enable requireCamelCaseOrUpperCaseIdentifiers
                            mediator.trigger('wirecard:datastorage:initialized', self.dataStorage);
                        });
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    mediator.execute('showErrorMessage', textStatus, errorThrown);
                }).always(function() {
                    self.initializingDataStorage = false;
                    mediator.execute('hideLoading');
                });
            }
        },

    });

    return WirecardPaymentDataInputComponent;
});
