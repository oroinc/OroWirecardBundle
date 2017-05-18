<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;

interface WirecardSeamlessConfigProviderInterface
{
    /**
     * @param string $identifier
     *
     * @return bool
     */
    public function hasPaymentConfig($identifier);

    /**
     * @return WirecardSeamlessConfigInterface[]
     */
    public function getPaymentConfigs();

    /**
     * @param string $identifier
     * @return WirecardSeamlessConfigInterface|null
     */
    public function getPaymentConfig($identifier);
}
