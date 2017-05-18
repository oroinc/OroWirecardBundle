<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser;

use GuzzleHttp\ClientInterface;
use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Request\WirecardRequestInterface;
use Hochstrasser\Wirecard\Response\WirecardResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\GatewayInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\RequestInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Response\Response;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Hochstrasser\NativeRequestBuilder\NativeRequestBuilderRegistry;

class Gateway implements GatewayInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var NativeRequestBuilderRegistry
     */
    protected $nativeRequestBuilderRegistry;

    /**
     * @param ClientInterface $client
     * @param NativeRequestBuilderRegistry $nativeRequestBuilderRegistry
     */
    public function __construct(ClientInterface $client, NativeRequestBuilderRegistry $nativeRequestBuilderRegistry)
    {
        $this->client = $client;
        $this->nativeRequestBuilderRegistry = $nativeRequestBuilderRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function request(RequestInterface $request, array $options = [])
    {
        $resolver = new OptionsResolver();
        $request->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $wirecardRequest = $this->nativeRequestBuilderRegistry
            ->getNativeRequestBuilder($request->getRequestIdentifier())
            ->createNativeRequest($options);

        $rawResponse = $this->client->send($wirecardRequest->createHttpRequest());
        $wirecardResponse = $wirecardRequest->createResponse($rawResponse);

        return new Response($wirecardResponse->toArray());
    }
}
