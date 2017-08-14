<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Provider;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\Provider\AbstractMethodProviderTest;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfig;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessCreditCardPaymentMethodFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessCreditCardConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Provider\WirecardSeamlessCreditCardMethodProvider;

class WirecardSeamlessCreditCardMethodProviderTest extends AbstractMethodProviderTest
{
    protected function setUp()
    {
        $this->configProvider = $this->createMock(WirecardSeamlessCreditCardConfigProviderInterface::class);
        $this->factory = $this->createMock(WirecardSeamlessCreditCardPaymentMethodFactoryInterface::class);
        $this->paymentConfigClass = WirecardSeamlessCreditCardConfig::class;
        $this->methodProvider = new WirecardSeamlessCreditCardMethodProvider($this->configProvider, $this->factory);
    }
}
