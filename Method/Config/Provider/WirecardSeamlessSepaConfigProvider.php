<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Doctrine\Common\Persistence\ManagerRegistry;
use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessSepaConfigFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessSepaConfigInterface;
use Psr\Log\LoggerInterface;

class WirecardSeamlessSepaConfigProvider extends WirecardSeamlessConfigProvider implements
    WirecardSeamlessSepaConfigProviderInterface
{
    /**
     * @var WirecardSeamlessSepaConfigInterface[]
     */
    protected $configs = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        WirecardSeamlessSepaConfigFactoryInterface $factory,
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
