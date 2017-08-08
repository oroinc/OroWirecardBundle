<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessCreditCardPaymentMethod;

class WirecardSeamlessCreditCardPaymentMethodTest extends WirecardSeamlessInitiateAwarePaymentMethodTest
{
    /**
     * {@inheritdoc}
     */
    protected function createMethod()
    {
        return new WirecardSeamlessCreditCardPaymentMethod(
            $this->config,
            $this->transactionProvider,
            $this->gateway,
            $this->router,
            $this->doctrineHelper,
            $this->requestStack,
            $this->optionsProvider
        );
    }

    public function testGetWirecardPaymentType()
    {
        $this->assertEquals('CCARD', $this->method->getWirecardPaymentType());
    }
}
