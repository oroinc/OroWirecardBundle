<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless;

use GuzzleHttp\ClientInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Request\RequestInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Gateway
{
    /** @var ClientInterface */
    protected $client;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(
        ClientInterface $client,
        RequestStack $requestStack
    ) {
        $this->client = $client;
        $this->requestStack = $requestStack;
    }

    public function request(RequestInterface $request, array $options = [])
    {
        $resolver = new OptionsResolver();
        $request->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $wirecardRequest = $request->buildWirecardRequest($options);
        $rawResponse = $this->client->send($wirecardRequest->createHttpRequest());

        return $wirecardRequest->createResponse($rawResponse);
    }

    public function getUserAgent()
    {
        return $this->requestStack->getMasterRequest()->headers->get('User-Agent');
    }

    public function getClientIp()
    {
        return $this->requestStack->getMasterRequest()->getClientIp();
    }
}
