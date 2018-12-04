<?php

namespace Oro\Bundle\WirecardBundle\Provider;

use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Provider\PaymentTransactionProvider as BasePaymentTransactionProvider;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessInitiateAwarePaymentMethodInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
        $customerUser = $this->customerUserProvider
            ? $this->customerUserProvider->getUser(false)
            : $this->getLoggedCustomerUser();

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

    /**
     * {@inheritdoc}
     */
    protected function getLoggedCustomerUser()
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof CustomerUser) {
            return $user;
        }

        return null;
    }
}
