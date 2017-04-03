<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessCreditCardPaymentMethod;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Gateway;
use Symfony\Component\Routing\RouterInterface;

class WirecardSeamlessCreditCardPaymentMethodTest extends WirecardSeamlessPaymentMethodTest
{
    /**
     * @inheritDoc
     */
    protected function createPaymentMethod(
        WirecardSeamlessConfigInterface $config,
        PaymentTransactionProvider $transactionProvider,
        Gateway $gateway,
        RouterInterface $router
    ) {
        return new WirecardSeamlessCreditCardPaymentMethod($config, $transactionProvider, $gateway, $router);
    }

    public function testGetWirecardPaymentType()
    {
        $this->assertEquals(WirecardSeamlessCreditCardPaymentMethod::TYPE, $this->method->getWirecardPaymentType());
    }
}
