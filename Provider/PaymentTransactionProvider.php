<?php

namespace Oro\Bundle\WirecardBundle\Provider;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Provider\PaymentTransactionProvider as BasePaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethodInterface;

/**
 * This provider searches active initiate payment transaction
 */
class PaymentTransactionProvider extends BasePaymentTransactionProvider
{
    /**
     * @param object $object
     * @param string $paymentMethod
     * @return PaymentTransaction|null
     */
    public function getActiveInitiatePaymentTransaction($object, $paymentMethod)
    {
        $customerUser = $this->customerUserProvider->getLoggedUserIncludingGuest();
        if (!$customerUser) {
            return null;
        }

        $criteria = [
            'active' => true,
            'action' => WirecardSeamlessInitiateAwarePaymentMethodInterface::INITIATE,
            'paymentMethod' => (string)$paymentMethod,
            'frontendOwner' => $customerUser,
        ];

        return $this->getPaymentTransaction($object, $criteria);
    }
}
