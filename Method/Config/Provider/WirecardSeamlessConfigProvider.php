<?php

namespace Oro\Bundle\WirecardBundle\Method\Config\Provider;

use Doctrine\Common\Persistence\ManagerRegistry;
use Oro\Bundle\WirecardBundle\Entity\WirecardSeamlessSettings;
use Oro\Bundle\WirecardBundle\Method\Config\Factory\WirecardSeamlessConfigFactoryInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessConfigInterface;
use Psr\Log\LoggerInterface;

abstract class WirecardSeamlessConfigProvider implements WirecardSeamlessConfigProviderInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var WirecardSeamlessConfigFactoryInterface
     */
    protected $factory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @return WirecardSeamlessConfigInterface[]
     */
    abstract public function getPaymentConfigs();

    /**
     * @param ManagerRegistry                        $doctrine
     * @param LoggerInterface                        $logger
     * @param WirecardSeamlessConfigFactoryInterface $factory
     * @param string                                 $type
     */
    public function __construct(
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        WirecardSeamlessConfigFactoryInterface $factory,
        $type
    ) {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->factory = $factory;
        $this->type = $type;
    }

    /**
     * @param string $identifier
     *
     * @return bool
     */
    public function hasPaymentConfig($identifier)
    {
        $configs = $this->getPaymentConfigs();

        return array_key_exists($identifier, $configs);
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return $this->type;
    }

    /**
     * @return WirecardSeamlessSettings[]
     */
    protected function getEnabledIntegrationSettings()
    {
        try {
            return $this->doctrine->getManagerForClass('OroWirecardBundle:WirecardSeamlessSettings')
                ->getRepository('OroWirecardBundle:WirecardSeamlessSettings')
                ->getEnabledSettingsByType($this->getType());
        } catch (\UnexpectedValueException $e) {
            $this->logger->critical($e->getMessage());

            return [];
        }
    }

    /**
     * @return array
     */
    protected function collectConfigs()
    {
        $configs = [];
        $settings = $this->getEnabledIntegrationSettings();

        foreach ($settings as $setting) {
            $config = $this->factory->createConfig($setting);
            $configs[$config->getPaymentMethodIdentifier()] = $config;
        }

        return $configs;
    }
}
