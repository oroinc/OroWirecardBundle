<?php

namespace Oro\Bundle\WirecardBundle\Request;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\PaymentBundle\Method\Provider\Registry\PaymentMethodProvidersRegistryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentMethodParamConverter implements ParamConverterInterface
{
    /**
     * @var PaymentMethodProvidersRegistryInterface
     */
    protected $methodProvidersRegistry;

    public function __construct(PaymentMethodProvidersRegistryInterface $methodProvidersRegistry)
    {
        $this->methodProvidersRegistry = $methodProvidersRegistry;
    }

    /**
     * @inheritDoc
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $param = $configuration->getName();

        if (!$request->attributes->has($param)) {
            return false;
        }

        $paymentMethodIdentifier = $request->attributes->get($param);

        foreach ($this->methodProvidersRegistry->getPaymentMethodProviders() as $provider) {
            if ($provider->hasPaymentMethod($paymentMethodIdentifier)) {
                $request->attributes->set($param, $provider->getPaymentMethod($paymentMethodIdentifier));
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return PaymentMethodInterface::class === $configuration->getClass();
    }
}
