<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPaypalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPaypalPaymentMethod;

class WirecardSeamlessPaypalPaymentMethodFactory extends WirecardSeamlessPaymentMethodFactory implements
    WirecardSeamlessPaypalPaymentMethodFactoryInterface
{
    /**
     * @param WirecardSeamlessPaypalConfigInterface $config
     *
     * @return PaymentMethodInterface
     */
    public function create(WirecardSeamlessPaypalConfigInterface $config)
    {
        return new WirecardSeamlessPaypalPaymentMethod(
            $config,
            $this->transactionProvider,
            $this->gateway,
            $this->router
        );
    }
}
