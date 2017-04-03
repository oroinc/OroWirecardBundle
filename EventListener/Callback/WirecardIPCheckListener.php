<?php

namespace Oro\Bundle\WirecardBundle\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\RequestStack;
use Oro\Bundle\PaymentBundle\Event\AbstractCallbackEvent;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option;

class WirecardIPCheckListener
{
    /**
     * @var string[]
     */
    private static $allowedIPs = [
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
     * @param RequestStack                   $requestStack
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

        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentTransaction->getPaymentMethod())) {
            return;
        }

        $masterRequest = $this->requestStack->getMasterRequest();
        if (null === $masterRequest) {
            $event->markFailed();

            return;
        }

        $requestIp = $masterRequest->getClientIp();
        $requestOptions = $paymentTransaction->getRequest();
        $testMode = isset($requestOptions[Option\TestMode::TESTMODE]) && $requestOptions[Option\TestMode::TESTMODE];

        if (!$testMode && !IpUtils::checkIp($requestIp, self::$allowedIPs)) {
            $event->markFailed();
        }
    }
}
