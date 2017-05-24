<?php

namespace Oro\Bundle\WirecardBundle\Request;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentMethodParamConverter implements ParamConverterInterface
{
    /**
     * @var PaymentMethodProviderInterface
     */
    protected $methodProvider;

    /**
     * PaymentMethodParamConverter constructor.
     * @param PaymentMethodProviderInterface $methodProvider
     */
    public function __construct(PaymentMethodProviderInterface $methodProvider)
    {
        $this->methodProvider = $methodProvider;
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

        if ($this->methodProvider->hasPaymentMethod($paymentMethodIdentifier)) {
            $request->attributes->set($param, $this->methodProvider->getPaymentMethod($paymentMethodIdentifier));

            return true;
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
