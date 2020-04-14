<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method\Factory;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfig;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessSepaPaymentMethodFactory;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessSepaPaymentMethodFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessSepaPaymentMethod;
use Oro\Bundle\WirecardBundle\OptionsProvider\OptionsProviderInterface;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\GatewayInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class WirecardSeamlessSepaPaymentMethodFactoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var WirecardSeamlessSepaPaymentMethodFactoryInterface */
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

    /** @var OptionsProviderInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $optionsProvider;

    protected function setUp(): void
    {
        $this->transactionProvider = $this->createMock(PaymentTransactionProvider::class);
        $this->gateway = $this->createMock(GatewayInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->doctrineHelper = $this->createMock(DoctrineHelper::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->optionsProvider = $this->createMock(OptionsProviderInterface::class);
        $this->factory = new WirecardSeamlessSepaPaymentMethodFactory(
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
        $config = $this->createMock(WirecardSeamlessSepaConfig::class);
        $paymentMethod = new WirecardSeamlessSepaPaymentMethod(
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
