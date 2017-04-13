<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

interface WirecardSeamlessConfigProviderInterface
{
    /**
     * @param string $identifier
     *
     * @return bool
     */
    public function hasPaymentConfig($identifier);
}
