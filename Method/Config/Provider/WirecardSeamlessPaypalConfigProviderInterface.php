<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;

interface WirecardSeamlessPaypalConfigProviderInterface extends WirecardSeamlessConfigProviderInterface
{
    /**
     * @return WirecardSeamlessPayPalConfigInterface[]
     */
    public function getPaymentConfigs();

    /**
     * @param string $identifier
     *
     * @return WirecardSeamlessPayPalConfigInterface|null
     */
    public function getPaymentConfig($identifier);
}
