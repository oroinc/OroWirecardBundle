<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Provider;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\Provider\AbstractMethodProviderTest;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfig;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessPayPalPaymentMethodFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessPayPalConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Provider\WirecardSeamlessPayPalMethodProvider;

class WirecardSeamlessPayPalMethodProviderTest extends AbstractMethodProviderTest
{
    protected function setUp()
    {
        $this->configProvider = $this->createMock(WirecardSeamlessPayPalConfigProviderInterface::class);
        $this->factory = $this->createMock(WirecardSeamlessPayPalPaymentMethodFactoryInterface::class);
        $this->paymentConfigClass = WirecardSeamlessPayPalConfig::class;
        $this->methodProvider = new WirecardSeamlessPayPalMethodProvider($this->configProvider, $this->factory);
    }
}
