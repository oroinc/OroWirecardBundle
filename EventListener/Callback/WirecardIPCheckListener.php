<?php

namespace Oro\Bundle\WirecardBundle\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Event\AbstractCallbackEvent;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\RequestStack;

class WirecardIPCheckListener
{
    /**
     * @var string[]
     */
    protected $allowedIPs = [
        '195.93.244.97',
        '185.60.56.35',
        '185.60.56.36',
    ];

    /**
     * @var PaymentMethodProviderInterface
     */
    protected $paymentMethodProvider;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param PaymentMethodProviderInterface $paymentMethodProvider
     * @param RequestStack $requestStack
     */
    public function __construct(
        PaymentMethodProviderInterface $paymentMethodProvider,
        RequestStack $requestStack
    ) {
        $this->paymentMethodProvider = $paymentMethodProvider;
        $this->requestStack = $requestStack;
    }

    /**
     * @param AbstractCallbackEvent $event
     */
    public function onNotify(AbstractCallbackEvent $event)
    {
        $paymentTransaction = $event->getPaymentTransaction();

        if (!$paymentTransaction) {
            return;
        }

        $paymentMethodIdentifier = $paymentTransaction->getPaymentMethod();

        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentMethodIdentifier)) {
            return;
        }

        $masterRequest = $this->requestStack->getMasterRequest();
        if (null === $masterRequest) {
            $event->markFailed();

            return;
        }

        $requestIp = $masterRequest->getClientIp();

        if (!IpUtils::checkIp($requestIp, $this->allowedIPs)) {
            $event->markFailed();
        }
    }
}
