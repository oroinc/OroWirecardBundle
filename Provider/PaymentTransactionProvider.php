<?php

namespace Oro\Bundle\WirecardBundle\Provider;

use Doctrine\Common\Collections\Criteria;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Provider\PaymentTransactionProvider as BasePaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPaymentMethod;

class PaymentTransactionProvider extends BasePaymentTransactionProvider
{
    /**
     * @param string $paymentMethod
     *
     * @return PaymentTransaction
     */
    public function getActiveInitiatePaymentTransaction($paymentMethod)
    {
        $customerUser = $this->getLoggedCustomerUser();
        if (!$customerUser) {
            return null;
        }

        /** @var PaymentTransaction $transaction */
        $transaction = $this->doctrineHelper->getEntityRepository($this->paymentTransactionClass)->findOneBy(
            [
                'active' => true,
                'action' => WirecardSeamlessPaymentMethod::INITIATE,
                'paymentMethod' => (string) $paymentMethod,
                'frontendOwner' => $customerUser,
            ],
            ['id' => Criteria::DESC]
        );

        return $transaction;
    }
}
