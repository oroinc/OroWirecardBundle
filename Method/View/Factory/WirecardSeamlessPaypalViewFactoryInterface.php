<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;

interface WirecardSeamlessPaypalViewFactoryInterface
{
    /**
     * @param WirecardSeamlessPayPalConfigInterface $config
     *
     * @return PaymentMethodViewInterface
     */
    public function create(WirecardSeamlessPayPalConfigInterface $config);
}
