Please refer first to [UPGRADE.md](UPGRADE.md) for the most important items that should be addressed before attempting to upgrade or during the upgrade of a vanilla Oro application.

The current file describes significant changes in the code that may affect the upgrade of your customizations.

## 4.0.0-rc (2019-05-29)
[Show detailed list of changes](incompatibilities-4-0-rc.md)

## 4.0.0-beta (2019-03-28)
### Changed
* In `Oro\Bundle\WirecardBundle\Controller\Frontend\AjaxWirecardController::initiateAction` 
 (`oro_wirecard_frontend_seamless_initiate` route)
 action the request method was changed to POST. 
