<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Factory;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\PaymentBundle\Provider\ExtractOptionsProvider;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfig;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessCreditCardPaymentMethodFactory;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessCreditCardPaymentMethodFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessCreditCardPaymentMethod;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\GatewayInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class WirecardSeamlessCreditCardPaymentMethodFactoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var WirecardSeamlessCreditCardPaymentMethodFactoryInterface */
    protected $factory;

    /** @var PaymentTransactionProvider|\PHPUnit\Framework\MockObject\MockObject */
    protected $transactionProvider;

    /** @var GatewayInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $gateway;

    /** @var RouterInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $router;

    /** @var DoctrineHelper|\PHPUnit\Framework\MockObject\MockObject */
    protected $doctrineHelper;

    /** @var RequestStack|\PHPUnit\Framework\MockObject\MockObject */
    protected $requestStack;

    /** @var ExtractOptionsProvider|\PHPUnit\Framework\MockObject\MockObject */
    protected $optionsProvider;

    protected function setUp()
    {
        $this->transactionProvider = $this->createMock(PaymentTransactionProvider::class);
        $this->gateway = $this->createMock(GatewayInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->doctrineHelper = $this->createMock(DoctrineHelper::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->optionsProvider = $this->createMock(ExtractOptionsProvider::class);
        $this->factory = new WirecardSeamlessCreditCardPaymentMethodFactory(
            $this->transactionProvider,
            $this->gateway,
            $this->router,
            $this->doctrineHelper,
            $this->requestStack,
            $this->optionsProvider
        );
    }

    public function testCreate()
    {
        $config = $this->createMock(WirecardSeamlessCreditCardConfig::class);
        $paymentMethod = new WirecardSeamlessCreditCardPaymentMethod(
            $config,
            $this->transactionProvider,
            $this->gateway,
            $this->router,
            $this->doctrineHelper,
            $this->requestStack,
            $this->optionsProvider
        );
        $this->assertEquals($paymentMethod, $this->factory->create($config));
    }
}
