<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessSepaPaymentMethod;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;

class WirecardSeamlessSepaPaymentMethodTest extends WirecardSeamlessInitiateAwarePaymentMethodTest
{
    /**
     * {@inheritdoc}
     */
    protected function createMethod()
    {
        return new WirecardSeamlessSepaPaymentMethod(
            $this->config,
            $this->transactionProvider,
            $this->gateway,
            $this->router,
            $this->doctrineHelper,
            $this->requestStack,
            $this->optionsProvider
        );
    }

    public function testGetWirecardPaymentType()
    {
        $this->assertEquals('SEPA-DD', $this->method->getWirecardPaymentType());
    }

    public function testPurchase()
    {
        $transaction = new PaymentTransaction();
        $transaction->setCurrency('USD');
        $transaction->setAmount(123.45);
        $transaction->setPaymentMethod(PaymentMethodInterface::PURCHASE);
        $transaction->setTransactionOptions(['checkoutId' => 123]);

        $initTransaction = $this->prepareInitiateTransaction();
        $this->prepareBaseOptions();
        $this->prepareShippingInfo();
        $this->prepareDoRequest();

        $this->assertEquals(['redirectTo' => 'http://test.local/redirect'], $this->method->purchase($transaction));

        $this->assertFalse($initTransaction->isActive());
        $this->assertTrue($transaction->isActive());
        $this->assertEquals([
            'language' => 'EN',
            'paymentType' => 'SEPA-DD',
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
            'consumerShippingZipCode' => 'postal code',
        ], $transaction->getRequest());

        $this->assertEquals([
            Response::REDIRECT_URL_FIELD => 'http://test.local/redirect',
            Response::ORDER_NUMBER_FIELD => 112233,
        ], $transaction->getResponse());
    }
}
