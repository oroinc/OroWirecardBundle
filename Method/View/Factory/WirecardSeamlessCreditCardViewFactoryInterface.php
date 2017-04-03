<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;

interface WirecardSeamlessCreditCardViewFactoryInterface
{
    /**
     * @param WirecardSeamlessCreditCardConfigInterface $config
     *
     * @return PaymentMethodViewInterface
     */
    public function create(WirecardSeamlessCreditCardConfigInterface $config);
}
