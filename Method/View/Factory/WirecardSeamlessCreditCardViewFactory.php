<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessCreditCardView;

class WirecardSeamlessCreditCardViewFactory extends WirecardSeamlessViewFactory implements
    WirecardSeamlessCreditCardViewFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(WirecardSeamlessCreditCardConfigInterface $config)
    {
        return new WirecardSeamlessCreditCardView($this->formFactory, $config);
    }
}
