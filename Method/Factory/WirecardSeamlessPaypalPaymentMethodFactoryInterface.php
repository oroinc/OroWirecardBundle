<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPaypalConfigInterface;

interface WirecardSeamlessPaypalPaymentMethodFactoryInterface
{
    /**
     * @param WirecardSeamlessPaypalConfigInterface $config
     *
     * @return PaymentMethodInterface
     */
    public function create(WirecardSeamlessPaypalConfigInterface $config);
}
