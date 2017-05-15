<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Doctrine\Common\Persistence\ManagerRegistry;
use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessPaypalConfigFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;
use Psr\Log\LoggerInterface;

class WirecardSeamlessPaypalConfigProvider extends WirecardSeamlessConfigProvider implements
    WirecardSeamlessPaypalConfigProviderInterface
{
    /**
     * @var WirecardSeamlessPayPalConfigInterface[]
     */
    protected $configs = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        WirecardSeamlessPaypalConfigFactoryInterface $factory,
        $type
    ) {
        parent::__construct($doctrine, $logger, $factory, $type);
    }

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
