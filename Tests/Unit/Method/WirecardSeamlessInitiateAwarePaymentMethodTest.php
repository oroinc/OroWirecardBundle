<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\OrderBundle\Entity\OrderAddress;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethod;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;

abstract class WirecardSeamlessInitiateAwarePaymentMethodTest extends AbstractWirecardSeamlessPaymentMethodTest
{
    /** @var WirecardSeamlessInitiateAwarePaymentMethod */
    protected $method;

    public function testInitiate()
    {
        $transaction = new PaymentTransaction();

        $this->prepareGetInitiateOptions();
        $this->prepareDoRequest();

        $this->method->initiate($transaction);

        $this->assertTrue($transaction->isActive());
        $this->assertArraySubset([
            'language' => 'EN',
            'returnUrl' => 'http://test.local',
        ], $transaction->getRequest());
        $this->assertArrayHasKey('orderIdent', $transaction->getRequest());

        $this->assertEquals([
            Response::REDIRECT_URL_FIELD => 'http://test.local/redirect',
            Response::ORDER_NUMBER_FIELD => 112233,
        ], $transaction->getResponse());
    }

    public function testPurchase()
    {
        $transaction = new PaymentTransaction();
        $transaction->setCurrency('USD');
        $transaction->setAmount(123.45);
        $transaction->setPaymentMethod(PaymentMethodInterface::PURCHASE);
        $transaction->setTransactionOptions(['checkoutId' => 123]);

        $initiateTransaction = $this->prepareInitiateTransaction();
        $this->prepareBaseOptions();
        $this->prepareShippingInfo();
        $this->prepareDoRequest();

        $this->assertEquals(['redirectTo' => 'http://test.local/redirect'], $this->method->purchase($transaction));

        $this->assertFalse($initiateTransaction->isActive());
        $this->assertTrue($transaction->isActive());
        $this->assertEquals([
            'language' => 'EN',
            'paymentType' => 'CCARD',
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
            'orderIdent' => 'test order ident',
            'storageId' => 'test storage id',
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

    public function testPurchaseWithoutCheckoutInOptions()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Appropriate checkout is not found');

        $transaction = new PaymentTransaction();

        $this->doctrineHelper->expects($this->never())
            ->method('getEntity');

        $this->method->purchase($transaction);
    }

    public function testPurchaseWithoutCheckout()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Appropriate checkout is not found');

        $transaction = new PaymentTransaction();

        $transaction->setTransactionOptions(['checkoutId' => 123]);
        $this->doctrineHelper->expects($this->once())
            ->method('getEntity')
            ->with(Checkout::class, 123)
            ->willReturn(null);

        $this->method->purchase($transaction);
    }

    public function testPurchaseWithoutInitiateTransaction()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Initiate payment transaction not found');

        $transaction = new PaymentTransaction();
        $transaction->setPaymentMethod('payment_method');

        $checkout = $this->getEntity(Checkout::class, ['id' => 123]);
        $transaction->setTransactionOptions(['checkoutId' => 123]);
        $this->doctrineHelper->expects($this->once())
            ->method('getEntity')
            ->with(Checkout::class, 123)
            ->willReturn($checkout);

        $this->transactionProvider->expects($this->once())
            ->method('getActiveInitiatePaymentTransaction')
            ->with($checkout, 'payment_method')
            ->willReturn(null);

        $this->method->purchase($transaction);
    }

    public function testSupports()
    {
        parent::testSupports();
        $this->assertTrue($this->method->supports(WirecardSeamlessInitiateAwarePaymentMethod::INITIATE));
    }

    protected function prepareGetInitiateOptions()
    {
        $this->config->expects($this->atLeastOnce())->method('getLanguageCode')->willReturn('EN');
        $this->router->expects($this->atLeastOnce())->method('generate')->willReturn('http://test.local');
    }

    /**
     * @return PaymentTransaction
     */
    protected function prepareInitiateTransaction()
    {
        $address = new OrderAddress();
        $checkout = $this->getEntity(Checkout::class, ['id' => 123]);
        $checkout->setShippingAddress($address);

        $this->doctrineHelper->expects($this->atLeastOnce())
            ->method('getEntity')
            ->with(Checkout::class, 123)
            ->willReturn($checkout);

        $initiateTransaction = new PaymentTransaction();
        $initiateTransaction->setRequest([Option\OrderIdent::ORDERIDENT => 'test order ident']);
        $initiateTransaction->setResponse([Option\StorageId::STORAGEID => 'test storage id']);

        $this->transactionProvider->expects($this->atLeastOnce())
            ->method('getActiveInitiatePaymentTransaction')
            ->with($checkout, PaymentMethodInterface::PURCHASE)
            ->willReturn($initiateTransaction);

        return $initiateTransaction;
    }
}
