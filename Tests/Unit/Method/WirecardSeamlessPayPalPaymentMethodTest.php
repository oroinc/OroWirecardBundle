<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\PaymentBundle\Provider\ExtractOptionsProvider;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPayPalPaymentMethod;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\GatewayInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class WirecardSeamlessPayPalPaymentMethodTest extends WirecardSeamlessPaymentMethodTest
{
    /**
     * @inheritDoc
     */
    protected function createPaymentMethod(
        WirecardSeamlessConfigInterface $config,
        PaymentTransactionProvider $transactionProvider,
        GatewayInterface $gateway,
        RouterInterface $router,
        DoctrineHelper $doctrineHelper,
        RequestStack $requestStack,
        ExtractOptionsProvider $optionsProvider
    ) {
        return new WirecardSeamlessPayPalPaymentMethod(
            $config,
            $transactionProvider,
            $gateway,
            $router,
            $doctrineHelper,
            $requestStack,
            $optionsProvider
        );
    }

    public function testGetWirecardPaymentType()
    {
        $this->assertEquals(WirecardSeamlessPayPalPaymentMethod::TYPE, $this->method->getWirecardPaymentType());
    }
}
