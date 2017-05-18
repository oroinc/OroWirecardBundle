<?php

namespace Oro\Bundle\WirecardBundle\Provider;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Provider\PaymentTransactionProvider as BasePaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethod;

class PaymentTransactionProvider extends BasePaymentTransactionProvider
{
    /**
     * @param object $object
     * @param string $paymentMethod
     * @return PaymentTransaction
     */
    public function getActiveInitiatePaymentTransaction($object, $paymentMethod)
    {
        $customerUser = $this->getLoggedCustomerUser();
        if (!$customerUser) {
            return null;
        }

        $criteria = [
                'active' => true,
                'action' => WirecardSeamlessInitiateAwarePaymentMethod::INITIATE,
                'paymentMethod' => (string) $paymentMethod,
                'frontendOwner' => $customerUser,
        ];

        return $this->getPaymentTransaction($object, $criteria);
    }
}
