/* eslint-disable camelcase, no-unused-vars */
function WirecardCEE_DataStorage() {
    this.storeCreditCardInformation = function(paymentTypeInformation, callback) {
        return this.storePaymentInformation(callback);
    };
    this.storeSepaDdInformation = function(paymentTypeInformation, callback) {
        return this.storePaymentInformation(callback);
    };
    this.storePaymentInformation = function(callback) {
        var responseObject = new WirecardCEE_Response();
        callback(responseObject);
    };
}

function WirecardCEE_Response() {
    this.getStatus = function() {
        return 0;
    };
}
