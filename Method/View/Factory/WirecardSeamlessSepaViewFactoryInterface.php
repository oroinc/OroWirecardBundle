<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;

interface WirecardSeamlessSepaViewFactoryInterface
{
    /**
     * @param WirecardSeamlessSepaConfigInterface $config
     *
     * @return PaymentMethodViewInterface
     */
    public function create(WirecardSeamlessSepaConfigInterface $config);
}
