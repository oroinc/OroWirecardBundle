define(function(require) {
    'use strict';

    var WirecardPaypalDataInputComponent;
    var WirecardPaymentDataInputComponent = require('orowirecard/js/app/components/input-wirecard-seamless');
    var mediator = require('oroui/js/mediator');

    WirecardPaypalDataInputComponent = WirecardPaymentDataInputComponent.extend({

        initialize: function(options) {
            WirecardPaypalDataInputComponent.__super__.initialize.apply(this, arguments);
            mediator.on('checkout:payment:before-transit', this.beforeTransit, this);
        },

        dispose: function() {
            if (this.disposed || !this.disposable) {
                return;
            }

            this.$el.off();
            mediator.off('checkout:payment:before-transit', this.beforeTransit, this);
            WirecardPaypalDataInputComponent.__super__.dispose.call(this);
        },

        /**
         * @param {Object} eventData
         */
        beforeTransit: function(eventData) {
            if (eventData.data.paymentMethod !== this.options.paymentMethod || eventData.stopped) {
                return;
            }

            eventData.stopped = true;
            mediator.execute('showLoading');

            this.initializeDataStorage(
                function() {
                    mediator.execute('hideLoading');
                    eventData.resume();
                },
                _.bind(this.dataStorageLoadFailed, this)
            );
        }
    });

    return WirecardPaypalDataInputComponent;
});