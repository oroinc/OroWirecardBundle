<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Hochstrasser\Wirecard\Model\Seamless\Frontend\InitPaymentResult;
use Hochstrasser\Wirecard\Response\WirecardResponse;
use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\PaymentBundle\Provider\ExtractOptionsProvider;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Method\AbstractWirecardSeamlessPaymentMethod;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethod;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\GatewayInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitDataStorageRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\InitPaymentRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class WirecardSeamlessPaymentMethodTest extends \PHPUnit_Framework_TestCase
{
    /** @var GatewayInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $gateway;

    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $router;

    /** @var AbstractWirecardSeamlessPaymentMethod */
    protected $method;

    /** @var WirecardSeamlessConfigInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $paymentConfig;

    /** @var PaymentTransactionProvider|\PHPUnit_Framework_MockObject_MockObject */
    protected $transactionProvider;

    /** @var DoctrineHelper|\PHPUnit_Framework_MockObject_MockObject */
    protected $doctrineHelper;

    /** @var RequestStack|\PHPUnit_Framework_MockObject_MockObject */
    protected $requestStack;

    /** @var ExtractOptionsProvider|\PHPUnit_Framework_MockObject_MockObject */
    protected $optionsProvider;

    /**
     * @param WirecardSeamlessConfigInterface $config
     * @param PaymentTransactionProvider $transactionProvider
     * @param GatewayInterface $gateway
     * @param RouterInterface $router
     * @param DoctrineHelper $doctrineHelper
     * @param RequestStack $requestStack
     * @param ExtractOptionsProvider $optionsProvider
     * @return AbstractWirecardSeamlessPaymentMethod
     */
    abstract protected function createPaymentMethod(
        WirecardSeamlessConfigInterface $config,
        PaymentTransactionProvider $transactionProvider,
        GatewayInterface $gateway,
        RouterInterface $router,
        DoctrineHelper $doctrineHelper,
        RequestStack $requestStack,
        ExtractOptionsProvider $optionsProvider
    );

    protected function setUp()
    {
        $this->markTestIncomplete('Skipped. Will be fixed in BB-9471');
        $this->gateway = $this->getMockBuilder(GatewayInterface::class)
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

        $this->doctrineHelper = $this->getMockBuilder(DoctrineHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->optionsProvider = $this->createMock(ExtractOptionsProvider::class);

        $this->method = $this->createPaymentMethod(
            $this->paymentConfig,
            $this->transactionProvider,
            $this->gateway,
            $this->router,
            $this->doctrineHelper,
            $this->requestStack,
            $this->optionsProvider
        );
    }

    public function testExecute()
    {
        $transaction = new PaymentTransaction();
        $transaction->setAction(WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE);

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
            [true, WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE],
            [true, PaymentMethodInterface::PURCHASE],
            [true, WirecardSeamlessInitiateAwarePaymentMethod::COMPLETE],
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
        $transaction->setAction(WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE);
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
        $transaction->setAction(WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE);
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
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Initiate payment transaction not found
     */
    public function testPurchaseWithoutInitiateTransaction()
    {
        $transaction = new PaymentTransaction();
        $transaction->setAction(PaymentMethodInterface::PURCHASE);
        $transaction->setTransactionOptions(['checkoutId' => 'a checkout id']);
        $transaction->setPaymentMethod('payment_method');

        $checkout = $this->createMock(Checkout::class);
        $this->doctrineHelper
            ->expects(static::once())
            ->method('getEntityReference')
            ->with(Checkout::class, 'a checkout id')
            ->willReturn($checkout);

        $this->transactionProvider->expects($this->once())
            ->method('getActiveInitiatePaymentTransaction')
            ->with($checkout, 'payment_method')
            ->willReturn(null);

        $this->gateway->expects($this->never())
            ->method('request');

        $this->method->purchase($transaction);
    }

    public function testPurchaseWithInitiateTransaction()
    {
        $this->configureCredentials();
        $this->configureLanguageCode();

        $transaction = new PaymentTransaction();
        $transaction->setAction(PaymentMethodInterface::PURCHASE);
        $transaction->setPaymentMethod('payment_method');
        $transaction->setTransactionOptions(
            [
                'checkoutId' => 0,
                'originalOption' => 'originalValue',
            ]
        );
        $transaction->setEntityClass(Order::class);
        $transaction->setEntityIdentifier(1);

        $checkout = $this->createMock(Checkout::class);
        $order = $this->createMock(Order::class);
        $order->expects(static::once())
            ->method('getIdentifier')
            ->willReturn('an order identifier');

        $this->doctrineHelper
            ->expects(static::at(0))
            ->method('getEntityReference')
            ->with(Checkout::class, 0)
            ->willReturn($checkout);
        $this->doctrineHelper
            ->expects(static::at(1))
            ->method('getEntityReference')
            ->with(Order::class, 1)
            ->willReturn($order);

        $initiateTransaction = new PaymentTransaction();
        $initiateTransaction->setAction(WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE);
        $initiateTransaction->setPaymentMethod('payment_method');
        $initiateTransaction->setRequest([Option\OrderIdent::ORDERIDENT => 'orderIdent']);
        $initiateTransaction->setResponse([Option\StorageId::STORAGEID => 'storageId']);

        $this->transactionProvider->expects($this->once())
            ->method('getActiveInitiatePaymentTransaction')
            ->with($checkout, 'payment_method')
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
        $headers = $this->createMock(HeaderBag::class);
        $headers->expects(static::once())
            ->method('get')
            ->with('User-Agent')
            ->willReturn('user agent');
        $request = $this->createMock(Request::class);
        $request->headers = $headers;
        $request->expects(static::once())
            ->method('getClientIp')
            ->willReturn('client ip');
        $this->requestStack->expects(static::any())
            ->method('getMasterRequest')
            ->willReturn($request);

        $this->assertEquals(
            ['redirectTo' => 'redirectURL'],
            $this->method->purchase($transaction)
        );

        $this->assertEquals(['responseFieldCode' => 'responseFieldValue'], $transaction->getResponse());
        $this->assertArrayNotHasKey(Option\Secret::SECRET, $transaction->getRequest());
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testGetInitPaymentOptions()
    {
        $this->configureCredentials();
        $this->configureLanguageCode();

        $transaction = new PaymentTransaction();
        $transaction->setAction(PaymentMethodInterface::PURCHASE);
        $transaction->setPaymentMethod('payment_method');
        $transaction->setTransactionOptions(
            [
                'checkoutId' => 0,
                'originalOption' => 'originalValue',
            ]
        );
        $transaction->setEntityClass(Order::class);
        $transaction->setEntityIdentifier(1);

        $checkout = $this->createMock(Checkout::class);
        $order = $this->createMock(Order::class);
        $order->expects(static::once())
            ->method('getIdentifier')
            ->willReturn('an order identifier');

        $this->doctrineHelper
            ->expects(static::at(0))
            ->method('getEntityReference')
            ->with(Checkout::class, 0)
            ->willReturn($checkout);
        $this->doctrineHelper
            ->expects(static::at(1))
            ->method('getEntityReference')
            ->with(Order::class, 1)
            ->willReturn($order);

        $initiateTransaction = new PaymentTransaction();
        $initiateTransaction->setAction(WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE);
        $initiateTransaction->setPaymentMethod('payment_method');
        $initiateTransaction->setRequest([Option\OrderIdent::ORDERIDENT => 'orderIdent']);
        $initiateTransaction->setResponse([Option\StorageId::STORAGEID => 'storageId']);

        $this->transactionProvider->expects($this->once())
            ->method('getActiveInitiatePaymentTransaction')
            ->with($checkout, 'payment_method')
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
        $headers = $this->createMock(HeaderBag::class);
        $headers->expects(static::once())
            ->method('get')
            ->with('User-Agent')
            ->willReturn('user agent');
        $request = $this->createMock(Request::class);
        $request->headers = $headers;
        $request->expects(static::once())
            ->method('getClientIp')
            ->willReturn('client ip');
        $this->requestStack->expects(static::any())
            ->method('getMasterRequest')
            ->willReturn($request);

        $this->router->expects($this->exactly(5))
            ->method('generate')
            ->withConsecutive(
                [
                    'oro_payment_callback_return',
                    [
                        'accessIdentifier' => $transaction->getAccessIdentifier(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL,
                ],
                [
                    'oro_payment_callback_error',
                    [
                        'accessIdentifier' => $transaction->getAccessIdentifier(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL,
                ],
                [
                    'oro_payment_callback_error',
                    [
                        'accessIdentifier' => $transaction->getAccessIdentifier(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL,
                ],
                [
                    'oro_payment_callback_notify',
                    [
                        'accessIdentifier' => $transaction->getAccessIdentifier(),
                        'accessToken' => $transaction->getAccessToken(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL,
                ],
                [
                    'oro_frontend_root',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL,
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
                'c' => 'c',
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
                'c' => 'c',
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
                'c' => 'c',
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
                'c' => 'c',
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
                'c' => 'c',
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
                'c' => 'c',
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
                'b' => 'b',
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
                'c' => 'c',
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
