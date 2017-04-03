<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPaypalPaymentMethod;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Gateway;
use Symfony\Component\Routing\RouterInterface;

class WirecardSeamlessPaypalPaymentMethodTest extends WirecardSeamlessPaymentMethodTest
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
        return new WirecardSeamlessPaypalPaymentMethod($config, $transactionProvider, $gateway, $router);
    }

    public function testGetWirecardPaymentType()
    {
        $this->assertEquals(WirecardSeamlessPaypalPaymentMethod::TYPE, $this->method->getWirecardPaymentType());
    }
}
