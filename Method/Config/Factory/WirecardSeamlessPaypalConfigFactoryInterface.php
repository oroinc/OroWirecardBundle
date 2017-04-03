<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPaypalConfigInterface;

interface WirecardSeamlessPaypalConfigFactoryInterface extends WirecardSeamlessConfigFactoryInterface
{
    /**
     * @param WirecardSeamlessSettings $settings
     *
     * @return WirecardSeamlessPaypalConfigInterface
     */
    public function createConfig(WirecardSeamlessSettings $settings);
}
