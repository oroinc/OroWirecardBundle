<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPaypalConfigInterface;

interface WirecardSeamlessPaypalConfigProviderInterface
{
    /**
     * @return WirecardSeamlessPaypalConfigInterface[]
     */
    public function getPaymentConfigs();

    /**
     * @param string $identifier
     *
     * @return WirecardSeamlessPaypalConfigInterface|null
     */
    public function getPaymentConfig($identifier);

    /**
     * @param string $identifier
     *
     * @return bool
     */
    public function hasPaymentConfig($identifier);
}
