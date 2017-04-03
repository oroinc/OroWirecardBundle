<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Hochstrasser\Wirecard\Model\Seamless\Frontend\InitPaymentResult;
use Hochstrasser\Wirecard\Response\WirecardResponse;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\DependencyInjection\OroWirecardExtension;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPaymentMethod;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Gateway;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitPaymentRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class WirecardSeamlessPaymentMethodTest
 * @package Oro\Bundle\WirecardBundle\Tests\Unit\Method
 *
 */
abstract class WirecardSeamlessPaymentMethodTest extends \PHPUnit_Framework_TestCase
{
    /** @var Gateway|\PHPUnit_Framework_MockObject_MockObject */
    protected $gateway;

    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $router;

    /** @var WirecardSeamlessPaymentMethod */
    protected $method;

    /** @var WirecardSeamlessConfigInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $paymentConfig;

    /** @var PaymentTransactionProvider|\PHPUnit_Framework_MockObject_MockObject */
    protected $transactionProvider;

    /**
     * @param WirecardSeamlessConfigInterface $config
     * @param PaymentTransactionProvider $transactionProvider
     * @param Gateway $gateway
     * @param RouterInterface $router
     * @return WirecardSeamlessPaymentMethod
     */
    abstract protected function createPaymentMethod(
        WirecardSeamlessConfigInterface $config,
        PaymentTransactionProvider $transactionProvider,
        Gateway $gateway,
        RouterInterface $router
    );

    protected function setUp()
    {
        $this->gateway = $this->getMockBuilder(Gateway::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->router = $this->getMockBuilder(RouterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentConfig =
            $this->createMock(WirecardSeamlessConfigInterface::class);

        $this->transactionProvider = $this->getMockBuilder(PaymentTransactionProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->method = $this->createPaymentMethod(
            $this->paymentConfig,
            $this->transactionProvider,
            $this->gateway,
            $this->router
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtensionAlias()
    {
        return OroWirecardExtension::ALIAS;
    }

    public function testExecute()
    {
        $transaction = new PaymentTransaction();
        $transaction->setAction(WirecardSeamlessPaymentMethod::INITIATE);

        $this->configureCredentials();
        $this->configureLanguageCode();

        $response = $this->getMockBuilder(WirecardResponse::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->gateway->expects($this->any())
            ->method('request')
            ->with($this->isInstanceOf(InitDataStorageRequest::class))
            ->willReturn($response);


        $this->method->execute($transaction->getAction(), $transaction);

        //$this->assertTrue($transaction->isSuccessful());
        $this->assertTrue($transaction->isActive());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported action "wrong_action"
     */
    public function testExecuteException()
    {
        $transaction = new PaymentTransaction();
        $transaction->setAction('wrong_action');

        $this->method->execute($transaction->getAction(), $transaction);
    }

    /**
     * @param bool $expected
     * @param string $actionName
     *
     * @dataProvider supportsDataProvider
     */
    public function testSupports($expected, $actionName)
    {
        $this->assertEquals($expected, $this->method->supports($actionName));
    }

    /**
     * @return array
     */
    public function supportsDataProvider()
    {
        return [
            [true, WirecardSeamlessPaymentMethod::INITIATE],
            [true, PaymentMethodInterface::PURCHASE],
            [true, WirecardSeamlessPaymentMethod::COMPLETE],
        ];
    }

    protected function configureCredentials()
    {
        $this->paymentConfig->expects($this->once())
            ->method('getCredentials')
            ->willReturn([
                Option\CustomerId::CUSTOMERID => 'customer',
                Option\ShopId::SHOPID => 'shop',
                Option\Secret::SECRET => 'secret',
            ]);
    }

    protected function configureLanguageCode()
    {
        $this->paymentConfig->expects($this->once())
            ->method('getLanguageCode')
            ->willReturn('language');
    }

    protected function configureHashingMethod()
    {
        $this->paymentConfig->expects($this->once())
            ->method('getHashingMethod')
            ->willReturn(Option\Hashing::DEFAULT_HASHING_METHOD);
    }

    public function testInitiateTransaction()
    {
        $this->configureCredentials();
        $this->configureLanguageCode();

        $transaction = new PaymentTransaction();
        $transaction->setAction(WirecardSeamlessPaymentMethod::INITIATE);
        $transaction->setPaymentMethod('payment_method');

        $response = $this->getMockBuilder(WirecardResponse::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('toArray')
            ->willReturn(['responseFieldCode' => 'responseFieldValue']);

        $this->gateway->expects($this->once())
            ->method('request')
            ->with($this->isInstanceOf(InitDataStorageRequest::class))
            ->willReturn($response);


        $this->method->initiate($transaction);

        //$this->assertTrue($transaction->isSuccessful());
        $this->assertTrue($transaction->isActive());
        $this->assertArrayNotHasKey(Option\Secret::SECRET, $transaction->getRequest());
        $this->assertEquals(['responseFieldCode' => 'responseFieldValue'], $transaction->getResponse());
    }

    public function testGetInitiateOptions()
    {
        $this->configureCredentials();
        $this->configureLanguageCode();

        $transaction = new PaymentTransaction();
        $transaction->setAction(WirecardSeamlessPaymentMethod::INITIATE);
        $transaction->setPaymentMethod('payment_method');

        $response = $this->getMockBuilder(WirecardResponse::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->gateway->expects($this->once())
            ->method('request')
            ->with($this->isInstanceOf(InitDataStorageRequest::class))
            ->willReturn($response);


        $this->router->expects($this->once())
            ->method('generate')
            ->with(
                'oro_checkout_frontend_checkout',
                [
                    'id' => $transaction->getEntityIdentifier(),
                ]
            )
            ->willReturnArgument(0);

        $this->method->initiate($transaction);

        $this->assertArrayHasKey(Option\CustomerId::CUSTOMERID, $transaction->getRequest());
        $this->assertArrayHasKey(Option\ShopId::SHOPID, $transaction->getRequest());
        $this->assertArrayHasKey(Option\Language::LANGUAGE, $transaction->getRequest());
        $this->assertArrayHasKey(Option\OrderIdent::ORDERIDENT, $transaction->getRequest());
        $this->assertArrayHasKey(Option\ReturnUrl::RETURNURL, $transaction->getRequest());
        $this->assertArrayHasKey(Option\TestMode::TESTMODE, $transaction->getRequest());
    }

    public function testPurchaseWithoutInitiateTransaction()
    {
        $transaction = new PaymentTransaction();
        $transaction->setAction(PaymentMethodInterface::PURCHASE);
        $transaction->setPaymentMethod('payment_method');

        $this->transactionProvider->expects($this->once())
            ->method('getActiveInitiatePaymentTransaction')
            ->with('payment_method')
            ->willReturn(null);


        $this->gateway->expects($this->never())
            ->method('request');

        $this->assertEquals(
            [],
            $this->method->purchase($transaction)
        );
    }

    public function testPurchaseWithInitiateTransaction()
    {
        $this->configureCredentials();
        $this->configureLanguageCode();

        $transaction = new PaymentTransaction();
        $transaction->setAction(PaymentMethodInterface::PURCHASE);
        $transaction->setPaymentMethod('payment_method');
        $transaction->setTransactionOptions(['originalOption' => 'originalValue']);

        $initiateTransaction = new PaymentTransaction();
        $initiateTransaction->setAction(WirecardSeamlessPaymentMethod::INITIATE);
        $initiateTransaction->setPaymentMethod('payment_method');
        $initiateTransaction->setRequest([Option\OrderIdent::ORDERIDENT => 'orderIdent']);
        $initiateTransaction->setResponse([Option\StorageId::STORAGEID => 'storageId']);

        $this->transactionProvider->expects($this->once())
            ->method('getActiveInitiatePaymentTransaction')
            ->with('payment_method')
            ->willReturn($initiateTransaction);

        $initPaymentResponse = $this->getMockBuilder(InitPaymentResult::class)
            ->disableOriginalConstructor()
            ->getMock();
        $initPaymentResponse->expects($this->once())
            ->method('getRedirectUrl')
            ->willReturn('redirectURL');

        $response = $this->getMockBuilder(WirecardResponse::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('toArray')
            ->willReturn(['responseFieldCode' => 'responseFieldValue']);
        $response->expects($this->once())
            ->method('toObject')
            ->willReturn($initPaymentResponse);

        $this->gateway->expects($this->once())
            ->method('request')
            ->with($this->isInstanceOf(InitPaymentRequest::class))
            ->willReturn($response);

        $this->assertEquals(
            ['redirectTo' => 'redirectURL'],
            $this->method->purchase($transaction)
        );

        $this->assertEquals(['responseFieldCode' => 'responseFieldValue'], $transaction->getResponse());
        $this->assertArrayNotHasKey(Option\Secret::SECRET, $transaction->getRequest());
    }

    public function testGetInitPaymentOptions()
    {
        $this->configureCredentials();
        $this->configureLanguageCode();

        $transaction = new PaymentTransaction();
        $transaction->setAction(PaymentMethodInterface::PURCHASE);
        $transaction->setPaymentMethod('payment_method');
        $transaction->setTransactionOptions(['originalOption' => 'originalValue']);

        $initiateTransaction = new PaymentTransaction();
        $initiateTransaction->setAction(WirecardSeamlessPaymentMethod::INITIATE);
        $initiateTransaction->setPaymentMethod('payment_method');
        $initiateTransaction->setRequest([Option\OrderIdent::ORDERIDENT => 'orderIdent']);
        $initiateTransaction->setResponse([Option\StorageId::STORAGEID => 'storageId']);

        $this->transactionProvider->expects($this->once())
            ->method('getActiveInitiatePaymentTransaction')
            ->with('payment_method')
            ->willReturn($initiateTransaction);

        $initPaymentResponse = $this->getMockBuilder(InitPaymentResult::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response = $this->getMockBuilder(WirecardResponse::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('toObject')
            ->willReturn($initPaymentResponse);

        $this->gateway->expects($this->once())
            ->method('request')
            ->with($this->isInstanceOf(InitPaymentRequest::class))
            ->willReturn($response);
        $this->gateway->expects($this->once())
            ->method('getUserAgent');
        $this->gateway->expects($this->once())
            ->method('getClientIp');

        $this->router->expects($this->exactly(5))
            ->method('generate')
            ->withConsecutive(
                [
                    'oro_payment_callback_return',
                    [
                        'accessIdentifier' => $transaction->getAccessIdentifier(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ],
                [
                    'oro_payment_callback_error',
                    [
                        'accessIdentifier' => $transaction->getAccessIdentifier(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ],
                [
                    'oro_payment_callback_error',
                    [
                        'accessIdentifier' => $transaction->getAccessIdentifier(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ],
                [
                    'oro_payment_callback_notify',
                    [
                        'accessIdentifier' => $transaction->getAccessIdentifier(),
                        'accessToken' => $transaction->getAccessToken(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ],
                [
                    'oro_frontend_root',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ]
            )
            ->willReturnArgument(0);

        $this->method->purchase($transaction);
        $this->assertArrayHasKey(Option\CustomerId::CUSTOMERID, $transaction->getRequest());
        $this->assertArrayHasKey(Option\ShopId::SHOPID, $transaction->getRequest());
        $this->assertArrayHasKey(Option\Language::LANGUAGE, $transaction->getRequest());
        $this->assertArrayHasKey(Option\OrderIdent::ORDERIDENT, $transaction->getRequest());
        $this->assertArrayHasKey(Option\PaymentType::PAYMENTTYPE, $transaction->getRequest());
        $this->assertArrayHasKey(Option\Amount::AMOUNT, $transaction->getRequest());
        $this->assertArrayHasKey(Option\Currency::CURRENCY, $transaction->getRequest());
        $this->assertArrayHasKey(Option\SuccessUrl::SUCCESSURL, $transaction->getRequest());
        $this->assertArrayHasKey(Option\CancelUrl::CANCELURL, $transaction->getRequest());
        $this->assertArrayHasKey(Option\FailureUrl::FAILUREURL, $transaction->getRequest());
        $this->assertArrayHasKey(Option\ConfirmUrl::CONFIRMURL, $transaction->getRequest());
        $this->assertArrayHasKey(Option\ServiceUrl::SERVICEURL, $transaction->getRequest());
        $this->assertArrayHasKey(Option\ConsumerUserAgent::CONSUMERUSERAGENT, $transaction->getRequest());
        $this->assertArrayHasKey(Option\ConsumerIpAddress::CONSUMERIPADDRESS, $transaction->getRequest());
        $this->assertArrayHasKey(Option\TestMode::TESTMODE, $transaction->getRequest());
    }

    public function testIsApplicable()
    {
        /** @var PaymentContextInterface|\PHPUnit_Framework_MockObject_MockObject $context */
        $context = $this->createMock(PaymentContextInterface::class);
        $this->assertTrue($this->method->isApplicable($context));
    }

    public function testComplete()
    {
        $this->configureCredentials();
        $this->configureHashingMethod();

        $response = new Response(
            [
                Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
                'a' => 'a',
                'b' => 'b',
                'c' => 'c'
            ]
        );

        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->setResponse(
            [
                Response::ORDER_NUMBER_FIELD => 'ref',
                Response::PAYMENT_STATE_FIELD => 'SUCCESS',
                Response::FINGERPRINT_FIELD =>
                    $response->calcFingerprint('secret', Option\Hashing::DEFAULT_HASHING_METHOD),
                Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
                'a' => 'a',
                'b' => 'b',
                'c' => 'c'
            ]
        );

        $this->assertEmpty($paymentTransaction->getReference());
        $this->method->complete($paymentTransaction);
        $this->assertEquals('ref', $paymentTransaction->getReference());
    }

    public function testCompleteSuccessfulFromResponse()
    {
        $this->configureCredentials();
        $this->configureHashingMethod();

        $response = new Response(
            [
                Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
                'a' => 'a',
                'b' => 'b',
                'c' => 'c'
            ]
        );

        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->setResponse(
            [
                Response::ORDER_NUMBER_FIELD => 'ref',
                Response::PAYMENT_STATE_FIELD => 'SUCCESS',
                Response::FINGERPRINT_FIELD =>
                    $response->calcFingerprint('secret', Option\Hashing::DEFAULT_HASHING_METHOD),
                Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
                'a' => 'a',
                'b' => 'b',
                'c' => 'c'
            ]
        );

        $this->assertFalse($paymentTransaction->isSuccessful());
        $this->method->complete($paymentTransaction);
        $this->assertTrue($paymentTransaction->isSuccessful());
    }

    public function testCompleteActiveFromResponse()
    {
        $this->configureCredentials();
        $this->configureHashingMethod();

        $response = new Response(
            [
                Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
                'a' => 'a',
                'b' => 'b',
                'c' => 'c'
            ]
        );

        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->setResponse(
            [
                Response::ORDER_NUMBER_FIELD => 'ref',
                Response::PAYMENT_STATE_FIELD => 'SUCCESS',
                Response::FINGERPRINT_FIELD =>
                    $response->calcFingerprint('secret', Option\Hashing::DEFAULT_HASHING_METHOD),
                Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
                'a' => 'a',
                'b' => 'b',
                'c' => 'c'
            ]
        );

        $this->assertFalse($paymentTransaction->isActive());
        $this->method->complete($paymentTransaction);
        $this->assertTrue($paymentTransaction->isActive());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Response fingerprint mismatch
     */
    public function testCompleteWithInvalidFingerprint()
    {
        $this->configureCredentials();
        $this->configureHashingMethod();

        $response = new Response(
            [
                Response::FINGERPRINT_ORDER_FIELD => 'a,b',
                'a' => 'a',
                'b' => 'b'
            ]
        );

        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->setResponse(
            [
                Response::ORDER_NUMBER_FIELD => 'ref',
                Response::PAYMENT_STATE_FIELD => 'SUCCESS',
                Response::FINGERPRINT_FIELD =>
                    $response->calcFingerprint('secret', Option\Hashing::DEFAULT_HASHING_METHOD),
                Response::FINGERPRINT_ORDER_FIELD => 'a,b,c',
                'a' => 'a',
                'b' => 'b',
                'c' => 'c'
            ]
        );

        $this->method->complete($paymentTransaction);
    }

    public function testCompleteFailureResponse()
    {
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->setResponse(
            [
                Response::PAYMENT_STATE_FIELD => 'FAIL',
            ]
        );

        $this->method->complete($paymentTransaction);
        $this->assertFalse($paymentTransaction->isSuccessful());
        $this->assertFalse($paymentTransaction->isActive());
        $this->assertEquals(null, $paymentTransaction->getReference());
    }


    public function testGetIdentifier()
    {
        $this->paymentConfig->expects(static::once())
            ->method('getPaymentMethodIdentifier')
            ->willReturn('wirecard_seamless');
        $this->assertSame('wirecard_seamless', $this->method->getIdentifier());
    }
}
