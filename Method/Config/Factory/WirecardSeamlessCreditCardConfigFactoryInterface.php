<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Factory;

use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;

interface WirecardSeamlessCreditCardConfigFactoryInterface extends WirecardSeamlessConfigFactoryInterface
{
    /**
     * @param WirecardSeamlessSettings $settings
     *
     * @return WirecardSeamlessCreditCardConfigInterface
     */
    public function createConfig(WirecardSeamlessSettings $settings);
}
