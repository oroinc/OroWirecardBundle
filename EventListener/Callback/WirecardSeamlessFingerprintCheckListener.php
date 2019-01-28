<?php

namespace Oro\Bundle\WirecardBundle\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Event\CallbackNotifyEvent;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Oro\Bundle\WirecardBundle\Method\AbstractWirecardSeamlessPaymentMethod;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\FingerprintCheckerInterface;
use Oro\Bundle\WirecardBundle\Wirecard\Seamless\Option\Secret;
use Psr\Log\LoggerAwareTrait;

class WirecardSeamlessFingerprintCheckListener
{
    use LoggerAwareTrait;

    /**
     * @var PaymentMethodProviderInterface
     */
    private $paymentMethodProvider;

    /**
     * @var WirecardSeamlessConfigProviderInterface
     */
    private $configProvider;

    /**
     * @var FingerprintCheckerInterface
     */
    private $fingerprintChecker;

    /**
     * @param PaymentMethodProviderInterface $paymentMethodProvider
     * @param WirecardSeamlessConfigProviderInterface $configProvider
     * @param FingerprintCheckerInterface $fingerprintChecker
     */
    public function __construct(
        PaymentMethodProviderInterface $paymentMethodProvider,
        WirecardSeamlessConfigProviderInterface $configProvider,
        FingerprintCheckerInterface $fingerprintChecker
    ) {
        $this->paymentMethodProvider = $paymentMethodProvider;
        $this->configProvider = $configProvider;
        $this->fingerprintChecker = $fingerprintChecker;
    }

    /**
     * @param CallbackNotifyEvent $event
     */
    public function onNotify(CallbackNotifyEvent $event)
    {
        $paymentTransaction = $event->getPaymentTransaction();

        if (!$paymentTransaction) {
            return;
        }

        $paymentMethodId = $paymentTransaction->getPaymentMethod();

        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentMethodId)) {
            return;
        }

        if (false === $this->configProvider->hasPaymentConfig($paymentMethodId)) {
            // Config must exist in case of payment method exist
            throw new \InvalidArgumentException('Can not find config');
        }

        $paymentMethodConfig = $this->configProvider->getPaymentConfig($paymentMethodId);
        $secret = array_intersect_key($paymentMethodConfig->getCredentials(), array_flip([Secret::SECRET]));
        $data = array_merge($event->getData(), $secret);

        if (false === $this->fingerprintChecker->check($paymentTransaction, $data)) {
            throw new \InvalidArgumentException('Fingerprint mismatch');
        }

        $paymentTransaction
            ->setResponse(array_replace($paymentTransaction->getResponse(), $event->getData()));

        try {
            $paymentMethod = $this->paymentMethodProvider->getPaymentMethod($paymentTransaction->getPaymentMethod());
            $paymentMethod->execute(AbstractWirecardSeamlessPaymentMethod::COMPLETE, $paymentTransaction);

            $event->markSuccessful();
        } catch (\InvalidArgumentException $e) {
            if ($this->logger) {
                // do not expose sensitive data in context
                $this->logger->error($e->getMessage(), []);
            }
        }
    }
}
