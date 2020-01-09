# OroWirecardBundle

OroWirecardBundle provides [integration](https://github.com/oroinc/platform/tree/master/src/Oro/Bundle/IntegrationBundle) with [Wirecard](https://www.wirecard.com/) Payment Gateway.

The bundle helps admin users to enable and configure **Wirecard Checkout Seamless** [payment method](https://github.com/oroinc/orocommerce/tree/master/src/Oro/Bundle/PaymentBundle) and therefore enable customers to pay for orders with [SEPA](https://en.wikipedia.org/wiki/Single_Euro_Payments_Area) direct debit, [PayPal](https://www.paypal.com/) payments, Credit and Debit cards.

## Setting Up the integration

Go to the "System -> Integrations" and click "Create Integration" button.

Select integration type "Wirecard Seamless Checkout" and fill required fields.
 
 - *Customer Id*: must be set to your Wirecard Customer ID 
 - *Shop Id*: must be filled with your Wirecard Shop ID
 - *Secret*: must be filled with your Wirecard Secret
 
## Test settings

For testing purposes use Wirecard test credentials. Check [Wirecard Seameless documentation](https://guides.wirecard.at/wcs:start)

Resources
---------

  * [OroCommerce Documentation](https://doc.oroinc.com)
  * [Contributing](https://doc.oroinc.com/community/contribute/)
