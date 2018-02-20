<?php

namespace Oro\Bundle\WirecardBundle\Tests\Behat\Context;

use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Oro\Bundle\TestFrameworkBundle\Behat\Context\OroFeatureContext;

class FeatureContext extends OroFeatureContext implements KernelAwareContext
{
    use KernelDictionary;

    /**
     * @Given There is EUR currency in the system configuration
     */
    public function thereIsEurCurrencyInTheSystemConfiguration()
    {
        $configManager = $this->getContainer()->get('oro_config.global');
        $configManager->set('oro_multi_currency.allowed_currencies', ['EUR', 'USD']);
        $configManager->set('oro_pricing_pro.enabled_currencies', ['EUR', 'USD']);
        $configManager->set('oro_pricing_pro.default_currency', 'EUR');
        $configManager->flush();
    }
}
