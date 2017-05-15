<?php

namespace Oro\Bundle\WirecardBundle\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Event\CallbackNotifyEvent;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPaymentMethod;
use Psr\Log\LoggerAwareTrait;

class WirecardSeamlessListener
{
    use LoggerAwareTrait;

    /**
     * @var PaymentMethodProviderInterface
     */
    protected $paymentMethodProvider;

    /**
     * @param PaymentMethodProviderInterface $paymentMethodProvider
     */
    public function __construct(PaymentMethodProviderInterface $paymentMethodProvider)
    {
        $this->paymentMethodProvider = $paymentMethodProvider;
    }

    /**
     * @param CallbackNotifyEvent $event
     */
    public function onNotify(CallbackNotifyEvent $event)
    {
        $paymentTransaction = $event->getPaymentTransaction();

        if (!$paymentTransaction || $paymentTransaction->getReference()) {
            return;
        }

        $paymentMethodId = $paymentTransaction->getPaymentMethod();

        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentMethodId)) {
            return;
        }

        $paymentTransaction
            ->setResponse(array_replace($paymentTransaction->getResponse(), $event->getData()));

        try {
            $paymentMethod = $this->paymentMethodProvider->getPaymentMethod($paymentTransaction->getPaymentMethod());
            $paymentMethod->execute(WirecardSeamlessPaymentMethod::COMPLETE, $paymentTransaction);

            $event->markSuccessful();
        } catch (\InvalidArgumentException $e) {
            if ($this->logger) {
                // do not expose sensitive data in context
                $this->logger->error($e->getMessage(), []);
            }
        }
    }
}
