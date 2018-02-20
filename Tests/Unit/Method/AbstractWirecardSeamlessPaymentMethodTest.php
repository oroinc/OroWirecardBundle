<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Method;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\OrderBundle\Entity\OrderAddress;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\PaymentBundle\Model\AddressOptionModel;
use Oro\Bundle\PaymentBundle\Provider\ExtractOptionsProvider;
use Oro\Bundle\WirecardBundle\Method\AbstractWirecardSeamlessPaymentMethod;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Oro\Bundle\WirecardBundle\Provider\PaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\GatewayInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\AbstractRequest;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;
use Oro\Component\Testing\Unit\EntityTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractWirecardSeamlessPaymentMethodTest extends \PHPUnit_Framework_TestCase
{
    use EntityTrait;

    /**
     * @var WirecardSeamlessConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    /**
     * @var PaymentTransactionProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $transactionProvider;

    /**
     * @var GatewayInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $gateway;

    /**
     * @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $router;

    /**
     * @var DoctrineHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $doctrineHelper;

    /**
     * @var RequestStack|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestStack;

    /**
     * @var ExtractOptionsProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $optionsProvider;

    /**
     * @var AbstractWirecardSeamlessPaymentMethod
     */
    protected $method;

    /**
     * @return AbstractWirecardSeamlessPaymentMethod
     */
    abstract protected function createMethod();

    public function setUp()
    {
        $this->config = $this->createMock(WirecardSeamlessConfigInterface::class);
        $this->transactionProvider = $this->createMock(PaymentTransactionProvider::class);
        $this->gateway = $this->createMock(GatewayInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->doctrineHelper = $this->createMock(DoctrineHelper::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->optionsProvider = $this->createMock(ExtractOptionsProvider::class);

        $this->method = $this->createMethod();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExecuteWrongAction()
    {
        $this->method->execute('some wrong action', new PaymentTransaction());
    }

    public function testCompleteSuccess()
    {
        $transaction = new PaymentTransaction();
        $transaction->setResponse([Response::PAYMENT_STATE_FIELD => 'SUCCESS']);

        $this->method->execute(AbstractWirecardSeamlessPaymentMethod::COMPLETE, $transaction);
        $this->assertFalse($transaction->isActive());
        $this->assertTrue($transaction->isSuccessful());
    }

    public function testCompleteFail()
    {
        $transaction = new PaymentTransaction();
        $transaction->setResponse([Response::PAYMENT_STATE_FIELD => 'FAILURE']);

        $this->method->complete($transaction);
        $this->assertFalse($transaction->isActive());
        $this->assertFalse($transaction->isSuccessful());
    }

    public function testSupports()
    {
        $this->assertTrue($this->method->supports(PaymentMethodInterface::PURCHASE));
        $this->assertTrue($this->method->supports(AbstractWirecardSeamlessPaymentMethod::COMPLETE));
        $this->assertFalse($this->method->supports('some wrong action'));
    }

    public function testIsApplicable()
    {
        $this->assertTrue($this->method->isApplicable($this->createMock(PaymentContextInterface::class)));
    }

    public function testGetIdentifier()
    {
        $this->config->expects($this->once())->method('getPaymentMethodIdentifier')->willReturn('payment identifier');
        $this->assertEquals('payment identifier', $this->method->getIdentifier());
    }

    protected function prepareBaseOptions()
    {
        $this->config->expects($this->atLeastOnce())->method('getLanguageCode')->willReturn('EN');
        $this->router->expects($this->atLeastOnce())->method('generate')->willReturn('http://test.local');

        $masterRequest = new Request();
        $masterRequest->headers->set('User-Agent', 'test-user-agent');
        $masterRequest->server->set('REMOTE_ADDR', '10.0.0.1');
        $this->requestStack->expects($this->atLeastOnce())->method('getMasterRequest')->willReturn($masterRequest);
    }

    protected function prepareShippingInfo()
    {
        $address = new OrderAddress();
        $checkout = $this->getEntity(Checkout::class, ['id' => 123]);
        $checkout->setShippingAddress($address);

        $this->doctrineHelper->expects($this->atLeastOnce())
            ->method('getEntity')
            ->with(Checkout::class, 123)
            ->willReturn($checkout);

        $this->doctrineHelper->expects($this->atLeastOnce())
            ->method('getEntityClass')
            ->with($address)
            ->willReturn(OrderAddress::class);

        $addressOptions = new AddressOptionModel();
        $addressOptions->setFirstName('test first name');
        $addressOptions->setLastName('test last name');
        $addressOptions->setStreet('street');
        $addressOptions->setStreet2('street 2');
        $addressOptions->setCity('city');
        $addressOptions->setRegionCode('region code');
        $addressOptions->setCountryIso2('USA');
        $addressOptions->setPostalCode('postal code');

        $this->optionsProvider->expects($this->atLeastOnce())
            ->method('getShippingAddressOptions')
            ->with(OrderAddress::class, $address)
            ->willReturn($addressOptions);
    }

    /**
     * @return Response
     */
    protected function prepareDoRequest()
    {
        $this->config->expects($this->atLeastOnce())->method('getCredentials')->willReturn([
            Option\CustomerId::CUSTOMERID => 'test customer id',
            Option\ShopId::SHOPID => 'test shop id',
            Option\Secret::SECRET => 'test secret',
        ]);

        $response = new Response([
            Response::REDIRECT_URL_FIELD => 'http://test.local/redirect',
            Response::ORDER_NUMBER_FIELD => 112233,
        ]);

        $this->gateway->expects($this->once())
            ->method('request')
            ->with(
                $this->isInstanceOf(AbstractRequest::class),
                $this->callback(function ($options) {
                    return is_array($options) && !empty($options);
                })
            )
            ->willReturn($response);

        return $response;
    }
}
