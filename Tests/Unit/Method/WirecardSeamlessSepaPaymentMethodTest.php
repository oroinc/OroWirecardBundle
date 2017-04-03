<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessSepaPaymentMethod;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Gateway;
use Symfony\Component\Routing\RouterInterface;

class WirecardSeamlessSepaPaymentMethodTest extends WirecardSeamlessPaymentMethodTest
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
        return new WirecardSeamlessSepaPaymentMethod($config, $transactionProvider, $gateway, $router);
    }

    public function testGetWirecardPaymentType()
    {
        $this->assertEquals(WirecardSeamlessSepaPaymentMethod::TYPE, $this->method->getWirecardPaymentType());
    }
}
