<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Provider;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\Provider\AbstractMethodProviderTest;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessSepaConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfig;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessSepaPaymentMethodFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Provider\WirecardSeamlessSepaMethodProvider;

class WirecardSeamlessSepaMethodProviderTest extends AbstractMethodProviderTest
{
    protected function setUp(): void
    {
        $this->configProvider = $this->createMock(WirecardSeamlessSepaConfigProviderInterface::class);
        $this->factory = $this->createMock(WirecardSeamlessSepaPaymentMethodFactoryInterface::class);
        $this->paymentConfigClass = WirecardSeamlessSepaConfig::class;
        $this->methodProvider = new WirecardSeamlessSepaMethodProvider($this->configProvider, $this->factory);
    }
}
