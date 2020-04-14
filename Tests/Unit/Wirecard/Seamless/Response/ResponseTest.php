<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Response;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    /** @var  Response */
    protected $response;

    /** @var  array */
    protected $params;

    protected function setUp(): void
    {
        $this->params =[
            Response::FINGERPRINT_FIELD => 'responseFingerprint',
            Response::FINGERPRINT_ORDER_FIELD => 'responseFingerprintOrder',
            Response::PAYMENT_STATE_FIELD => 'SUCCESS',
            Response::GATEWAY_REFERENCE_NUMBER_FIELD => 'gatewayReferenceNumber',
            Response::ORDER_NUMBER_FIELD => 'orderNumber',
            Response::REDIRECT_URL_FIELD => 'redirectUrl',
        ];

        $this->response = new Response($this->params);
    }

    public function testIsSuccessful()
    {
        self::assertTrue($this->response->isSuccessful());
    }

    public function testIsSuccessfulWithWrongParam()
    {
        $this->response = new Response([Response::PAYMENT_STATE_FIELD => true]);
        self::assertFalse($this->response->isSuccessful());
    }

    public function testGetPaymentState()
    {
        self::assertSame('SUCCESS', $this->response->getPaymentState());
    }

    public function testGetFingerprint()
    {
        self::assertSame('responseFingerprint', $this->response->getFingerprint());
    }

    public function testGetFingerprintOrder()
    {
        self::assertSame('responseFingerprintOrder', $this->response->getFingerprintOrder());
    }

    public function testGetGatewayReferenceNumber()
    {
        self::assertSame('gatewayReferenceNumber', $this->response->getGatewayReferenceNumber());
    }

    public function testGetOrderNumber()
    {
        self::assertSame('orderNumber', $this->response->getOrderNumber());
    }

    public function testGetRedirectUrl()
    {
        self::assertSame('redirectUrl', $this->response->getRedirectUrl());
    }

    public function testGetOffset()
    {
        self::assertSame('SUCCESS', $this->response->getOffset(Response::PAYMENT_STATE_FIELD));
    }

    public function testGetData()
    {
        self::assertSame($this->params, $this->response->getData());
    }
}
