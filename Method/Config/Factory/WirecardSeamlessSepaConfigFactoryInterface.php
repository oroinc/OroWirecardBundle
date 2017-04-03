<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;

interface WirecardSeamlessSepaConfigFactoryInterface extends WirecardSeamlessConfigFactoryInterface
{
    /**
     * @param WirecardSeamlessSettings $settings
     *
     * @return WirecardSeamlessSepaConfigInterface
     */
    public function createConfig(WirecardSeamlessSettings $settings);
}
