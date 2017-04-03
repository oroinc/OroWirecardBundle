<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPaypalConfigInterface;

interface WirecardSeamlessPaypalViewFactoryInterface
{
    /**
     * @param WirecardSeamlessPaypalConfigInterface $config
     *
     * @return PaymentMethodViewInterface
     */
    public function create(WirecardSeamlessPaypalConfigInterface $config);
}
