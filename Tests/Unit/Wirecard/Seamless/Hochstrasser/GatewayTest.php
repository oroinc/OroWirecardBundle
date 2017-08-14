<?php

namespace Oro\Bundle\WirecardBundle\Tests\Unit\Wirecard\Seamless\Hochstrasser;

use GuzzleHttp\ClientInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Hochstrasser\Wirecard\Request\WirecardRequestInterface;
use Hochstrasser\Wirecard\Response\WirecardResponse;

use \Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\RequestInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\Gateway;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\NativeRequestBuilderRegistry;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\NativeRequestBuilderInterface;

class GatewayTest extends \PHPUnit_Framework_TestCase
{
    /** @var Gateway */
    protected $gateway;

    /** @var ClientInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $client;

    /** @var NativeRequestBuilderRegistry |\PHPUnit_Framework_MockObject_MockObject */
    protected $nativeRequestBuilderRegistry;

    public function setUp()
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->nativeRequestBuilderRegistry = $this->createMock(NativeRequestBuilderRegistry::class);
        $this->gateway = new Gateway($this->client, $this->nativeRequestBuilderRegistry);
    }

    public function testRequest()
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())->method('configureOptions')
            ->with($this->isInstanceOf(OptionsResolver::class));

        $rawResponse = $this->createMock(ResponseInterface::class);

        $wirecardResponse = $this->createMock(WirecardResponse::class);
        $wirecardResponse->expects($this->once())->method('toArray')->willReturn(['ping' => '200']);

        $psrRequestInterface = $this->createMock(PsrRequestInterface::class);

        $wireCardRequest = $this->createMock(WirecardRequestInterface::class);
        $wireCardRequest->expects($this->once())->method('createHttpRequest')
            ->willReturn($psrRequestInterface);
        $wireCardRequest->expects($this->once())->method('createResponse')->with($rawResponse)
            ->willReturn($wirecardResponse);

        $requestBuilder = $this->createMock(NativeRequestBuilderInterface::class);
        $requestBuilder->expects($this->once())->method('createNativeRequest')
            ->with([])->willReturn($wireCardRequest);

        $this->nativeRequestBuilderRegistry->expects($this->once())->method('getNativeRequestBuilder')
            ->willReturn($requestBuilder);

        $this->client->expects($this->once())->method('send')->with($psrRequestInterface)
            ->willReturn($rawResponse);

        $response = $this->gateway->request($request, []);

        self::assertEquals(['ping' => '200'], $response->getData());
    }
}
