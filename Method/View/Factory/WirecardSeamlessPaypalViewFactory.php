<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessPaypalView;

class WirecardSeamlessPaypalViewFactory extends WirecardSeamlessViewFactory implements
    WirecardSeamlessPaypalViewFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(WirecardSeamlessPayPalConfigInterface $config)
    {
        return new WirecardSeamlessPaypalView($this->formFactory, $config);
    }
}
