<?php

namespace Oro\Bundle\WirecardBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;
use Oro\Bundle\WirecardBundle\Method\WirecardSeamlessSepaPaymentMethod;

class WirecardSeamlessSepaPaymentMethodFactory extends WirecardSeamlessPaymentMethodFactory implements
    WirecardSeamlessSepaPaymentMethodFactoryInterface
{
    /**
     * @param WirecardSeamlessSepaConfigInterface $config
     *
     * @return PaymentMethodInterface
     */
    public function create(WirecardSeamlessSepaConfigInterface $config)
    {
        return new WirecardSeamlessSepaPaymentMethod(
            $config,
            $this->transactionProvider,
            $this->gateway,
            $this->router,
            $this->doctrineHelper,
            $this->requestStack
        );
    }
}
