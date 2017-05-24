<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;

class WirecardSeamlessPayPalConfigProvider extends WirecardSeamlessConfigProvider implements
    WirecardSeamlessPayPalConfigProviderInterface
{
    /**
     * @var WirecardSeamlessPayPalConfigInterface[]
     */
    protected $configs = [];

    /**
     * {@inheritdoc}
     */
    public function getPaymentConfigs()
    {
        if (0 === count($this->configs)) {
            return $this->configs = $this->collectConfigs();
        }

        return $this->configs;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentConfig($identifier)
    {
        if (!$this->hasPaymentConfig($identifier)) {
            return null;
        }

        $configs = $this->getPaymentConfigs();

        return $configs[$identifier];
    }
}
