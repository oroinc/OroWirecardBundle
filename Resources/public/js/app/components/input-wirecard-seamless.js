/* global WirecardCEE_DataStorage */
define(function(require) {
    'use strict';

    const _ = require('underscore');
    const __ = require('orotranslation/js/translator');
    const $ = require('jquery');
    const mediator = require('oroui/js/mediator');
    const routing = require('routing');
    const scriptjs = require('scriptjs');
    const BaseComponent = require('oroui/js/app/components/base/component');
    require('jquery.validate');

    const WirecardPaymentDataInputComponent = BaseComponent.extend({
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

        /**
         * @inheritDoc
         */
        constructor: function WirecardPaymentDataInputComponent(options) {
            WirecardPaymentDataInputComponent.__super__.constructor.call(this, options);
        },

        /**
         * @inheritDoc
         */
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
            let appendElement;
            if (elementSelector) {
                const element = this.$form.find(elementSelector);
                const parentForm = element.closest('form');

                if (elementSelector !== this.options.selectors.expirationDate && parentForm.length) {
                    return element.validate().form();
                }

                appendElement = element.clone();
            } else {
                appendElement = this.$form.clone();
            }

            const virtualForm = $('<form>');
            virtualForm.append(appendElement);

            const self = this;
            const validator = virtualForm.validate({
                ignore: '', // required to validate all fields in virtual form
                errorPlacement: function(error, element) {
                    const $el = self.$form.find('#' + $(element).attr('id'));
                    const parentWithValidation = $el.parents(self.options.validationSelector);

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

            let errors;

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

            const self = this;
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
                    const numberOfErrors = parseInt(data.errors);
                    for (let i = 1; i <= numberOfErrors; i++) {
                        mediator.execute(
                            'showErrorMessage',
                            data.error[i].consumerMessage,
                            data.error[i].errorCode + ':' + data.error[i].message
                        );
                    }
                    return failCallback();
                } else {
                    scriptjs(data.javascriptUrl, function() {
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
