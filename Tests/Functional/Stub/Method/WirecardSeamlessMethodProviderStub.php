<?php

namespace Oro\Bundle\WirecardBundle\Tests\Functional\Stub\Method;

use Oro\Bundle\PaymentBundle\Method\Provider\AbstractPaymentMethodProvider;

class WirecardSeamlessMethodProviderStub extends AbstractPaymentMethodProvider
{
    /**
     * {@inheritDoc}
     */
    protected function collectMethods()
    {
        $this->addMethod(WirecardSeamlessMethodStub::TYPE, new WirecardSeamlessMethodStub());
    }
}
