<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless;

use GuzzleHttp\ClientInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\RequestInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Gateway
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * Gateway constructor.
     * @param ClientInterface $client
     */
    public function __construct(
        ClientInterface $client
    ) {
        $this->client = $client;
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return \Hochstrasser\Wirecard\Response\WirecardResponse
     */
    public function request(RequestInterface $request, array $options = [])
    {
        $resolver = new OptionsResolver();
        $request->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $wirecardRequest = $request->buildWirecardRequest($options);
        $rawResponse = $this->client->send($wirecardRequest->createHttpRequest());

        return $wirecardRequest->createResponse($rawResponse);
    }
}
