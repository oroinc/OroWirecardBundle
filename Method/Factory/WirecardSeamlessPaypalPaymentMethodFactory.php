<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessPaypalPaymentMethod;

class WirecardSeamlessPaypalPaymentMethodFactory extends WirecardSeamlessPaymentMethodFactory implements
    WirecardSeamlessPaypalPaymentMethodFactoryInterface
{
    /**
     * @param WirecardSeamlessPayPalConfigInterface $config
     *
     * @return PaymentMethodInterface
     */
    public function create(WirecardSeamlessPayPalConfigInterface $config)
    {
        return new WirecardSeamlessPaypalPaymentMethod(
            $config,
            $this->transactionProvider,
            $this->gateway,
            $this->router,
            $this->doctrineHelper,
            $this->requestStack
        );
    }
}
