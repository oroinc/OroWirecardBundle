<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;

interface WirecardSeamlessSepaConfigProviderInterface
{
    /**
     * @return WirecardSeamlessSepaConfigInterface[]
     */
    public function getPaymentConfigs();

    /**
     * @param string $identifier
     *
     * @return WirecardSeamlessSepaConfigInterface|null
     */
    public function getPaymentConfig($identifier);

    /**
     * @param string $identifier
     *
     * @return bool
     */
    public function hasPaymentConfig($identifier);
}
