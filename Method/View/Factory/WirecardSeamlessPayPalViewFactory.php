<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessPayPalView;

class WirecardSeamlessPayPalViewFactory extends WirecardSeamlessViewFactory implements
    WirecardSeamlessPayPalViewFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(WirecardSeamlessPayPalConfigInterface $config)
    {
        return new WirecardSeamlessPayPalView($this->formFactory, $config);
    }
}
