<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;

interface WirecardSeamlessPayPalPaymentMethodFactoryInterface
{
    /**
     * @param WirecardSeamlessPayPalConfigInterface $config
     *
     * @return PaymentMethodInterface
     */
    public function create(WirecardSeamlessPayPalConfigInterface $config);
}
