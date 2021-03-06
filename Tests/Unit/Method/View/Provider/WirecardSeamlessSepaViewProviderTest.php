<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\View\Provider\AbstractMethodViewProviderTest;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessSepaConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessSepaViewFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\View\Provider\WirecardSeamlessSepaViewProvider;

class WirecardSeamlessSepaViewProviderTest extends AbstractMethodViewProviderTest
{
    protected function setUp(): void
    {
        $this->factory = $this->createMock(WirecardSeamlessSepaViewFactoryInterface::class);
        $this->configProvider = $this->createMock(WirecardSeamlessSepaConfigProviderInterface::class);
        $this->paymentConfigClass = WirecardSeamlessSepaConfigInterface::class;

        $this->provider = new WirecardSeamlessSepaViewProvider($this->factory, $this->configProvider);
    }
}
