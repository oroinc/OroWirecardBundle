<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\View\Provider\AbstractMethodViewProviderTest;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessPayPalConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessPayPalViewFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\View\Provider\WirecardSeamlessPayPalViewProvider;

class WirecardSeamlessPayPalViewProviderTest extends AbstractMethodViewProviderTest
{
    protected function setUp(): void
    {
        $this->factory = $this->createMock(WirecardSeamlessPayPalViewFactoryInterface::class);
        $this->configProvider = $this->createMock(WirecardSeamlessPayPalConfigProviderInterface::class);
        $this->paymentConfigClass = WirecardSeamlessPayPalConfigInterface::class;

        $this->provider = new WirecardSeamlessPayPalViewProvider($this->factory, $this->configProvider);
    }
}
