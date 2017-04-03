<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;

interface WirecardSeamlessCreditCardPaymentMethodFactoryInterface
{
    /**
     * @param WirecardSeamlessCreditCardConfigInterface $config
     *
     * @return PaymentMethodInterface
     */
    public function create(WirecardSeamlessCreditCardConfigInterface $config);
}
