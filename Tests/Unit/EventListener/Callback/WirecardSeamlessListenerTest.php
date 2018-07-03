<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\DependencyInjection\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Event\CallbackNotifyEvent;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Oro\Bundle\WirecardBundle\EventListener\Callback\WirecardSeamlessListener;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class WirecardSeamlessListenerTest extends \PHPUnit\Framework\TestCase
{
    /** @var PaymentMethodProviderInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $paymentMethodProvider;

    /** @var WirecardSeamlessListener */
    protected $listener;

    /** @var LoggerInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $logger;

    protected function setUp()
    {
        $this->paymentMethodProvider = $this->createMock(PaymentMethodProviderInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->listener = new WirecardSeamlessListener($this->paymentMethodProvider);
        $this->listener->setLogger($this->logger);
    }

    public function testOnNotify()
    {
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction
            ->setAction('action')
            ->setPaymentMethod('payment_method')
            ->setResponse(['existing' => 'response']);

        $paymentMethod = $this->createMock(PaymentMethodInterface::class);
        $paymentMethod
            ->expects(static::once())
            ->method('execute')
            ->with('complete', $paymentTransaction);

        $this->paymentMethodProvider
            ->expects(static::once())
            ->method('hasPaymentMethod')
            ->with('payment_method')
            ->willReturn(true);
        $this->paymentMethodProvider
            ->expects(static::once())
            ->method('getPaymentMethod')
            ->with('payment_method')
            ->willReturn($paymentMethod);

        $event = new CallbackNotifyEvent(['paymentState' => 'SUCCESS']);
        $event->setPaymentTransaction($paymentTransaction);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
        $this->listener->onNotify($event);
        $this->assertEquals('action', $paymentTransaction->getAction());
        $this->assertEquals(Response::HTTP_OK, $event->getResponse()->getStatusCode());
        $this->assertEquals(
            ['paymentState' => 'SUCCESS', 'existing' => 'response'],
            $paymentTransaction->getResponse()
        );
    }

    public function testOnNotifyExecuteFailed()
    {
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction
            ->setAction('action')
            ->setPaymentMethod('payment_method')
            ->setResponse(['existing' => 'response']);

        $paymentMethod = $this->createMock(PaymentMethodInterface::class);
        $paymentMethod->expects($this->once())
            ->method('execute')
            ->willThrowException(new \InvalidArgumentException());

        $this->paymentMethodProvider->expects(static::any())
            ->method('hasPaymentMethod')
            ->with('payment_method')
            ->willReturn(true);
        $this->paymentMethodProvider->expects(static::any())
            ->method('getPaymentMethod')
            ->with('payment_method')
            ->willReturn($paymentMethod);

        $event = new CallbackNotifyEvent(['paymentState' => 'SUCCESS']);
        $event->setPaymentTransaction($paymentTransaction);

        $this->logger->expects($this->once())->method('error')->with(
            $this->isType('string'),
            $this->logicalAnd(
                $this->isType('array'),
                $this->isEmpty()
            )
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
        $this->listener->onNotify($event);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
    }

    public function testOnNotifyWithWrongTransaction()
    {
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction
            ->setPaymentMethod('payment_method');

        $this->paymentMethodProvider->expects(static::any())
            ->method('hasPaymentMethod')
            ->with('payment_method')
            ->willReturn(false);

        $this->paymentMethodProvider->expects(static::never())
            ->method('getPaymentMethod')
            ->with('payment_method');

        $event = new CallbackNotifyEvent(['paymentState' => 'SUCCESS']);
        $event->setPaymentTransaction($paymentTransaction);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
        $this->listener->onNotify($event);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
    }

    public function testOnNotifyTransactionWithReference()
    {
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction
            ->setPaymentMethod('payment_method')
            ->setAction('action')
            ->setReference('reference');

        $event = new CallbackNotifyEvent(['paymentState' => 'SUCCESS']);
        $event->setPaymentTransaction($paymentTransaction);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
        $this->listener->onNotify($event);
        $this->assertEquals('action', $paymentTransaction->getAction());
        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
    }

    public function testOnNotifyWithoutTransaction()
    {
        $event = new CallbackNotifyEvent(['paymentState' => 'SUCCESS']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
        $this->listener->onNotify($event);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
    }
}
