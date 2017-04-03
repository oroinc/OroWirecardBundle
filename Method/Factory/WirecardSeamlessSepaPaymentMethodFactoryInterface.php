<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;

interface WirecardSeamlessSepaPaymentMethodFactoryInterface
{
    /**
     * @param WirecardSeamlessSepaConfigInterface $config
     *
     * @return PaymentMethodInterface
     */
    public function create(WirecardSeamlessSepaConfigInterface $config);
}
