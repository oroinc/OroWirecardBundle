<?php

namespace Oro\Bundle\WirecardBundle\Method\View\Factory;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;
use Oro\Bundle\WirecardBundle\Method\View\WirecardSeamlessSepaView;

class WirecardSeamlessSepaViewFactory extends WirecardSeamlessViewFactory implements
    WirecardSeamlessSepaViewFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(WirecardSeamlessSepaConfigInterface $config)
    {
        return new WirecardSeamlessSepaView($this->formFactory, $config);
    }
}
