<?php

namespace Oro\Bundle\WirecardBundle\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Oro\Bundle\PaymentBundle\Event\AbstractCallbackEvent;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessConfigProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessConfigProviderInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * @var WirecardSeamlessConfigProvider
     */
    protected $configProvider;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param PaymentMethodProviderInterface $paymentMethodProvider
     * @param WirecardSeamlessConfigProviderInterface $configProvider
     * @param RequestStack $requestStack
     */
    public function __construct(
        PaymentMethodProviderInterface $paymentMethodProvider,
        WirecardSeamlessConfigProviderInterface $configProvider,
        RequestStack $requestStack
    ) {
        $this->paymentMethodProvider = $paymentMethodProvider;
        $this->configProvider = $configProvider;
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

        $configs = $this->configProvider->getPaymentConfigs();
        if (!isset($configs[$paymentTransaction->getPaymentMethod()])) {
            return;
        }

        $config = $configs[$paymentTransaction->getPaymentMethod()];

        $masterRequest = $this->requestStack->getMasterRequest();
        if (null === $masterRequest) {
            $event->markFailed();

            return;
        }

        $requestIp = $masterRequest->getClientIp();

        if (!$config->isTestMode() && !IpUtils::checkIp($requestIp, self::$allowedIPs)) {
            $event->markFailed();
        }
    }
}
