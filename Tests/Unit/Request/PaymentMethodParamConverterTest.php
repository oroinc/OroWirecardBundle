<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Request;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Oro\Bundle\WirecardBundle\Request\PaymentMethodParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class PaymentMethodParamConverterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PaymentMethodParamConverter
     */
    protected $paymentMethodParamConverter;

    /**
     * @var ParamConverter|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configuration;

    /**
     * @var ParameterBag|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestAttributes;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var PaymentMethodProviderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $paymentMethodProvider;

    public function setUp()
    {
        $this->requestAttributes = $this->createMock(ParameterBag::class);
        $this->request = new Request();
        $this->request->attributes = $this->requestAttributes;
        $this->paymentMethodProvider = $this->createMock(PaymentMethodProviderInterface::class);
        $this->configuration = $this->createMock(ParamConverter::class);
        $this->paymentMethodParamConverter = new PaymentMethodParamConverter($this->paymentMethodProvider);
    }

    public function testApply()
    {
        $this->configuration->expects($this->once())->method('getName')->willReturn('test');

        $this->requestAttributes
            ->expects($this->once())
            ->method('has')
            ->with($this->equalTo('test'))
            ->willReturn(true);
        $this->requestAttributes
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('test'))
            ->willReturn('testIdentifier');
        $this->requestAttributes
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo('test'), $this->equalTo('testValue'));

        $this->paymentMethodProvider
            ->expects($this->once())
            ->method('hasPaymentMethod')
            ->with($this->equalTo('testIdentifier'))
            ->willReturn(true);
        $this->paymentMethodProvider
            ->expects($this->once())
            ->method('getPaymentMethod')
            ->with($this->equalTo('testIdentifier'))
            ->willReturn('testValue');

        self::assertTrue($this->paymentMethodParamConverter->apply($this->request, $this->configuration));
    }

    public function testApplyWithoutConfigurationParam()
    {
        $this->configuration->expects($this->once())->method('getName')->willReturn(null);
        $this->paymentMethodProvider->expects($this->never())->method('hasPaymentMethod');
        $this->paymentMethodProvider->expects($this->never())->method('getPaymentMethod');

        self::assertFalse($this->paymentMethodParamConverter->apply($this->request, $this->configuration));
    }

    public function testApplyWithoutRequestValue()
    {
        $this->configuration->expects($this->once())->method('getName')->willReturn('test');
        $this->requestAttributes
            ->expects($this->once())
            ->method('has')
            ->with($this->equalTo('test'))
            ->willReturn(false);
        $this->paymentMethodProvider->expects($this->never())->method('hasPaymentMethod');
        $this->paymentMethodProvider->expects($this->never())->method('getPaymentMethod');

        self::assertFalse($this->paymentMethodParamConverter->apply($this->request, $this->configuration));
    }

    public function testSupports()
    {
        $this->configuration->expects($this->exactly(2))->method('getClass')
            ->willReturn(PaymentMethodInterface::class);

        self::assertTrue($this->paymentMethodParamConverter->supports($this->configuration));
    }

    public function testSupportsWithNullableConfigurationParam()
    {
        $this->configuration->expects($this->once())->method('getClass')
            ->willReturn(null);

        self::assertFalse($this->paymentMethodParamConverter->supports($this->configuration));
    }

    public function testSupportsWithWrongPaymentMethodClass()
    {
        $this->configuration->expects($this->exactly(2))->method('getClass')
            ->willReturn('blablabla');

        self::assertFalse($this->paymentMethodParamConverter->supports($this->configuration));
    }
}
