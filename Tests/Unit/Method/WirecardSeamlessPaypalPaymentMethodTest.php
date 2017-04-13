<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPaypalPaymentMethod;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Gateway;
use Symfony\Component\HttpFoundation\RequestStack;
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
        RouterInterface $router,
        DoctrineHelper $doctrineHelper,
        RequestStack $requestStack
    ) {
        return new WirecardSeamlessPaypalPaymentMethod(
            $config,
            $transactionProvider,
            $gateway,
            $router,
            $doctrineHelper,
            $requestStack
        );
    }

    public function testGetWirecardPaymentType()
    {
        $this->assertEquals(WirecardSeamlessPaypalPaymentMethod::TYPE, $this->method->getWirecardPaymentType());
    }
}
