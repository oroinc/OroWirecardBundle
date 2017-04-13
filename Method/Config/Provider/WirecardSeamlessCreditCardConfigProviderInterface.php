<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessCreditCardConfigInterface;

interface WirecardSeamlessCreditCardConfigProviderInterface extends WirecardSeamlessConfigProviderInterface
{
    /**
     * @return WirecardSeamlessCreditCardConfigInterface[]
     */
    public function getPaymentConfigs();

    /**
     * @param string $identifier
     *
     * @return WirecardSeamlessCreditCardConfigInterface|null
     */
    public function getPaymentConfig($identifier);
}
