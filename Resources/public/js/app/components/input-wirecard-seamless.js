define(function(require) {
    'use strict';

    var WirecardPaymentDataInputComponent;
    var _ = require('underscore');
    var __ = require('orotranslation/js/translator');
    var $ = require('jquery');
    var mediator = require('oroui/js/mediator');
    var routing = require('routing');
    var BaseComponent = require('oroui/js/app/components/base/component');
    require('jquery.validate');

    WirecardPaymentDataInputComponent = BaseComponent.extend({
        options: {
            messages: {
                communication_err: 'oro.wirecard.communication_err'
            },
            sourceEntityId: null,
            paymentMethod: null,
            validationSelector: '[data-validation]',
            initiatePaymentMethodRoute: 'oro_wirecard_seamless_initiate',
            formSelector: '[data-wirecard-form]',
            wirecardErrorsSelector: '[data-wirecard-errors]'
        },

        /**
         * @property {jQuery}
         */
        $el: null,

        /**
         * @property {jQuery}
         */
        $form: null,

        /**
         * @property {Object|null}
         */
        dataStorage: null,

        /**
         * @property {Boolean}
         */
        disposable: true,

        initialize: function(options) {
            this.options = _.extend({}, this.options, options);

            this.$el = this.options._sourceElement;
            this.$form = this.$el.find(this.options.formSelector);

            mediator.on('checkout:payment:before-hide-filled-form', this.beforeHideFilledForm, this);
            mediator.on('checkout:payment:before-restore-filled-form', this.beforeRestoreFilledForm, this);
            mediator.on('checkout:payment:remove-filled-form', this.removeFilledForm, this);
        },

        dispose: function() {
            if (this.disposed || !this.disposable) {
                return;
            }

            mediator.off('checkout:payment:before-hide-filled-form', this.beforeHideFilledForm, this);
            mediator.off('checkout:payment:before-restore-filled-form', this.beforeRestoreFilledForm, this);
            mediator.off('checkout:payment:remove-filled-form', this.removeFilledForm, this);

            WirecardPaymentDataInputComponent.__super__.dispose.call(this);
        },

        /**
         * @param {String} elementSelector
         */
        validate: function(elementSelector) {
            var appendElement;
            if (elementSelector) {
                var element = this.$form.find(elementSelector);
                var parentForm = element.closest('form');

                if (elementSelector !== this.options.selectors.expirationDate && parentForm.length) {
                    return element.validate().form();
                }

                appendElement = element.clone();
            } else {
                appendElement = this.$form.clone();
            }

            var virtualForm = $('<form>');
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
                // set new select to value of old select
                // http://stackoverflow.com/questions/742810/clone-isnt-cloning-select-values
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

        initializeDataStorage: function(successCallback, failCallback) {
            if (this.dataStorage) {
                return successCallback();
            }

            var self = this;
            $.ajax({
                url: routing.generate(
                    this.options.initiatePaymentMethodRoute,
                    {
                        id: this.options.sourceEntityId,
                        paymentMethod: this.options.paymentMethod
                    }
                ),
                type: 'POST'
            }).done(function(data) {
                if (data.errors) {
                    var numberOfErrors = parseInt(data.errors);
                    for (var i = 1; i <= numberOfErrors; i++) {
                        mediator.execute(
                            'showErrorMessage',
                            data.error[i].consumerMessage,
                            data.error[i].errorCode + ':' + data.error[i].message
                        );
                    }
                    return failCallback();
                } else {
                    require([data.javascriptUrl], function() {
                        self.dataStorage = new WirecardCEE_DataStorage();
                        return successCallback();
                    });
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                self.logError(errorThrown);
                return failCallback();
            });
        },

        dataStorageLoadFailed: function() {
            mediator.execute('hideLoading');
            mediator.execute('showErrorMessage', __(this.options.messages.communication_err));
        },

        /**
         * @param {(string|Object)} message
         */
        logError: function(message) {
            if (typeof window.console === 'undefined') {
                // can not log error because console doesn't exist
                return;
            }
            window.console.error(message);
        },

        beforeHideFilledForm: function() {
            this.disposable = false;
        },

        beforeRestoreFilledForm: function() {
            if (this.disposable) {
                this.dispose();
            }
        },

        removeFilledForm: function() {
            // Remove hidden form js component
            if (!this.disposable) {
                this.disposable = true;
                this.dispose();
            }
        }
    });

    return WirecardPaymentDataInputComponent;
});
