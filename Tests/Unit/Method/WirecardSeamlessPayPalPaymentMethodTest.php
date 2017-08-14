<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPayPalPaymentMethod;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;

class WirecardSeamlessPayPalPaymentMethodTest extends AbstractWirecardSeamlessPaymentMethodTest
{
    /**
     * @var WirecardSeamlessPayPalPaymentMethod
     */
    protected $method;

    /**
     * {@inheritdoc}
     */
    protected function createMethod()
    {
        return new WirecardSeamlessPayPalPaymentMethod(
            $this->config,
            $this->transactionProvider,
            $this->gateway,
            $this->router,
            $this->doctrineHelper,
            $this->requestStack,
            $this->optionsProvider
        );
    }

    public function testPurchase()
    {
        $transaction = new PaymentTransaction();
        $transaction->setCurrency('USD');
        $transaction->setAmount(123.45);
        $transaction->setTransactionOptions(['checkoutId' => 123]);

        $this->prepareBaseOptions();
        $this->prepareShippingInfo();
        $this->prepareDoRequest();

        $this->assertEquals(['redirectTo' => 'http://test.local/redirect'], $this->method->purchase($transaction));

        $this->assertTrue($transaction->isActive());
        $this->assertEquals([
            'language' => 'EN',
            'paymentType' => 'PAYPAL',
            'amount' => '123.45',
            'currency' => 'USD',
            'orderDescription' => 'Checkout: 123',
            'successUrl' => 'http://test.local',
            'cancelUrl' => 'http://test.local',
            'failureUrl' => 'http://test.local',
            'confirmUrl' => 'http://test.local',
            'serviceUrl' => 'http://test.local',
            'consumerUserAgent' => 'test-user-agent',
            'consumerIpAddress' => '10.0.0.1',
            'consumerShippingFirstName' => 'test first name',
            'consumerShippingLastName' => 'test last name',
            'consumerShippingAddress1' => 'street',
            'consumerShippingAddress2' => 'street 2',
            'consumerShippingCity' => 'city',
            'consumerShippingState' => 'region code',
            'consumerShippingCountry' => 'USA',
            'consumerShippingZipCode' => 'postal code'
        ], $transaction->getRequest());

        $this->assertEquals([
            Response::REDIRECT_URL_FIELD => 'http://test.local/redirect',
            Response::ORDER_NUMBER_FIELD => 112233,
        ], $transaction->getResponse());
    }

    public function testPurchaseWithoutMasterRequest()
    {
        $transaction = new PaymentTransaction();
        $transaction->setCurrency('USD');
        $transaction->setAmount(123.45);
        $transaction->setTransactionOptions(['checkoutId' => 123]);

        $this->config->expects($this->atLeastOnce())->method('getLanguageCode')->willReturn('EN');
        $this->router->expects($this->atLeastOnce())->method('generate')->willReturn('http://test.local');

        $this->requestStack->expects($this->atLeastOnce())->method('getMasterRequest')->willReturn(null);

        $this->prepareShippingInfo();
        $this->prepareDoRequest();

        $this->assertEquals(['redirectTo' => 'http://test.local/redirect'], $this->method->purchase($transaction));

        $this->assertTrue($transaction->isActive());
        $this->assertEquals([
            'language' => 'EN',
            'paymentType' => 'PAYPAL',
            'amount' => '123.45',
            'currency' => 'USD',
            'orderDescription' => 'Checkout: 123',
            'successUrl' => 'http://test.local',
            'cancelUrl' => 'http://test.local',
            'failureUrl' => 'http://test.local',
            'confirmUrl' => 'http://test.local',
            'serviceUrl' => 'http://test.local',
            'consumerUserAgent' => '',
            'consumerIpAddress' => '',
            'consumerShippingFirstName' => 'test first name',
            'consumerShippingLastName' => 'test last name',
            'consumerShippingAddress1' => 'street',
            'consumerShippingAddress2' => 'street 2',
            'consumerShippingCity' => 'city',
            'consumerShippingState' => 'region code',
            'consumerShippingCountry' => 'USA',
            'consumerShippingZipCode' => 'postal code'
        ], $transaction->getRequest());

        $this->assertEquals([
            Response::REDIRECT_URL_FIELD => 'http://test.local/redirect',
            Response::ORDER_NUMBER_FIELD => 112233,
        ], $transaction->getResponse());
    }

    public function testPurchaseWithoutCheckout()
    {
        $transaction = new PaymentTransaction();
        $transaction->setCurrency('USD');
        $transaction->setAmount(123.45);
        $transaction->setTransactionOptions(['checkoutId' => 123]);

        $this->prepareBaseOptions();

        $this->doctrineHelper->expects($this->atLeastOnce())
            ->method('getEntity')
            ->with(Checkout::class, 123)
            ->willReturn(null);

        $this->prepareDoRequest();

        $this->assertEquals(['redirectTo' => 'http://test.local/redirect'], $this->method->purchase($transaction));

        $this->assertTrue($transaction->isActive());
        $this->assertEquals([
            'language' => 'EN',
            'paymentType' => 'PAYPAL',
            'amount' => '123.45',
            'currency' => 'USD',
            'orderDescription' => 'Checkout: 123',
            'successUrl' => 'http://test.local',
            'cancelUrl' => 'http://test.local',
            'failureUrl' => 'http://test.local',
            'confirmUrl' => 'http://test.local',
            'serviceUrl' => 'http://test.local',
            'consumerUserAgent' => 'test-user-agent',
            'consumerIpAddress' => '10.0.0.1',
        ], $transaction->getRequest());

        $this->assertEquals([
            Response::REDIRECT_URL_FIELD => 'http://test.local/redirect',
            Response::ORDER_NUMBER_FIELD => 112233,
        ], $transaction->getResponse());
    }

    public function testPurchaseWithoutShippingAddress()
    {
        $transaction = new PaymentTransaction();
        $transaction->setCurrency('USD');
        $transaction->setAmount(123.45);
        $transaction->setTransactionOptions(['checkoutId' => 123]);

        $checkout = $this->getEntity(Checkout::class, ['id' => 123]);

        $this->prepareBaseOptions();

        $this->doctrineHelper->expects($this->atLeastOnce())
            ->method('getEntity')
            ->with(Checkout::class, 123)
            ->willReturn($checkout);

        $this->prepareDoRequest();

        $this->assertEquals(['redirectTo' => 'http://test.local/redirect'], $this->method->purchase($transaction));

        $this->assertTrue($transaction->isActive());
        $this->assertEquals([
            'language' => 'EN',
            'paymentType' => 'PAYPAL',
            'amount' => '123.45',
            'currency' => 'USD',
            'orderDescription' => 'Checkout: 123',
            'successUrl' => 'http://test.local',
            'cancelUrl' => 'http://test.local',
            'failureUrl' => 'http://test.local',
            'confirmUrl' => 'http://test.local',
            'serviceUrl' => 'http://test.local',
            'consumerUserAgent' => 'test-user-agent',
            'consumerIpAddress' => '10.0.0.1',
        ], $transaction->getRequest());

        $this->assertEquals([
            Response::REDIRECT_URL_FIELD => 'http://test.local/redirect',
            Response::ORDER_NUMBER_FIELD => 112233,
        ], $transaction->getResponse());
    }

    public function testGetWirecardPaymentType()
    {
        $this->assertEquals('PAYPAL', $this->method->getWirecardPaymentType());
    }
}
