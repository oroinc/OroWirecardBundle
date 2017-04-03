# OroWirecardBundle

This Bundle provides integration with [Wirecard](https://www.wirecard.com/) for OroCommerce.
It provides payment processing through Wirecard payment provider services.


## Setting Up the Connection

First of all, a new Integration with type "Wirecard" must be created.

Go to the "System -> Integrations" and click "Create Integration" button.
 
 - *Customer Id*: must be set to your Wirecard Customer ID 
 - *Shop Id*: must be filled with your Wirecard Shop ID
 - *Secret*: must be filled with your Wirecard Secret
 
## Test settings

For testing purposes use Wirecard test credentials. Check [Wirecard Seameless documentation](https://guides.wirecard.at/wcs:start)
The test mode checkbox on integration settings disable IP checks for notification callbacks. This is useful to debug  Wirecard through ngrok.

## Known Issues