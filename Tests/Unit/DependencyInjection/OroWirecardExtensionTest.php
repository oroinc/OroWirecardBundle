<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\DependencyInjection;

use Oro\Bundle\TestFrameworkBundle\Test\DependencyInjection\ExtensionTestCase;
use Oro\Bundle\WirecardBundle\DependencyInjection\OroWirecardExtension;

class OroWirecardExtensionTest extends ExtensionTestCase
{
    public function testLoad()
    {
        $this->loadExtension(new OroWirecardExtension());

        $expectedDefinitions = [
            'oro_wirecard.integation.wirecard_seamless.channel',
            'oro_wirecard.integration.wirecard_seamless.transport',
            'oro_wirecard.method.generator.identifier.wirecard_seamless.credit_card',
            'oro_wirecard.method.generator.identifier.wirecard_seamless.paypal',
            'oro_wirecard.method.generator.identifier.wirecard_seamless.sepa_direct_debit',
            'oro_wirecard.method.provider.wirecard_seamless.credit_card',
            'oro_wirecard.method.provider.wirecard_seamless.paypal',
            'oro_wirecard.method.provider.wirecard_seamless.sepa_direct_debit',
            'oro_wirecard.method.view.provider.wirecard_seamless.credit_card',
            'oro_wirecard.method.view.provider.wirecard_seamless.paypal',
            'oro_wirecard.method.view.provider.wirecard_seamless.sepa_direct_debit',
            'oro_wirecard.event_listener.callback.wirecard_seamless.credit_card',
            'oro_wirecard.event_listener.callback.wirecard_seamless.paypal',
            'oro_wirecard.event_listener.callback.wirecard_seamless.sepa_direct_debit',
            'oro_wirecard.event_listener.callback.ip_check.wirecard_seamless.credit_card',
            'oro_wirecard.event_listener.callback.ip_check.wirecard_seamless.paypal',
            'oro_wirecard.event_listener.callback.ip_check.wirecard_seamless.sepa_direct_debit',
        ];
        $this->assertDefinitionsLoaded($expectedDefinitions);
    }
}
