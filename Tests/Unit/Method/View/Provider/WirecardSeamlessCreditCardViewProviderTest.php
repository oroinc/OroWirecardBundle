<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\View\Provider\AbstractMethodViewProviderTest;
use Oro\Bundle\WirecardBundle\Method\View\Provider\WirecardSeamlessCreditCardViewProvider;
use Oro\Bundle\WirecardBundle\Method\View\Factory\WirecardSeamlessCreditCardViewFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessCreditCardConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;

class WirecardSeamlessCreditCardViewProviderTest extends AbstractMethodViewProviderTest
{
    public function setUp()
    {
        $this->factory = $this->createMock(WirecardSeamlessCreditCardViewFactoryInterface::class);
        $this->configProvider = $this->createMock(WirecardSeamlessCreditCardConfigProviderInterface::class);
        $this->paymentConfigClass = WirecardSeamlessCreditCardConfigInterface::class;

        $this->provider = new WirecardSeamlessCreditCardViewProvider($this->factory, $this->configProvider);
    }
}
