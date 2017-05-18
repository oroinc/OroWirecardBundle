<?php

namespace Oro\Bundle\WirecardBundle\Wirecard\Seamless;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;

interface FingerprintCheckerInterface
{
    /**
     * @param PaymentTransaction $paymentTransaction
     * @param array $data
     * @return bool
     */
    public function check(PaymentTransaction $paymentTransaction, array $data);
}
