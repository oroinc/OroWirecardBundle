<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessCreditCardPaymentMethod;

class WirecardSeamlessCreditCardPaymentMethodFactory extends WirecardSeamlessPaymentMethodFactory implements
    WirecardSeamlessCreditCardPaymentMethodFactoryInterface
{
    /**
     * @param WirecardSeamlessCreditCardConfigInterface $config
     *
     * @return PaymentMethodInterface
     */
    public function create(WirecardSeamlessCreditCardConfigInterface $config)
    {
        return new WirecardSeamlessCreditCardPaymentMethod(
            $config,
            $this->transactionProvider,
            $this->gateway,
            $this->router
        );
    }
}
