<?php

namespace Oro\Bundle\WirecardBundle\Method\Provider;

use Oro\Bundle\PaymentBundle\Method\Provider\AbstractPaymentMethodProvider;
use Oro\Bundle\WirecardBundle\Method\Config\Provider\WirecardSeamlessPayPalConfigProviderInterface;
use Oro\Bundle\WirecardBundle\Method\Config\WirecardSeamlessPayPalConfigInterface;
use Oro\Bundle\WirecardBundle\Method\Factory\WirecardSeamlessPayPalPaymentMethodFactoryInterface;

class WirecardSeamlessPayPalMethodProvider extends AbstractPaymentMethodProvider
{
    /**
     * @var WirecardSeamlessPayPalPaymentMethodFactoryInterface
     */
    private $factory;

    /**
     * @var WirecardSeamlessPayPalConfigProviderInterface
     */
    private $configProvider;

    /**
     * @param WirecardSeamlessPayPalConfigProviderInterface $configProvider
     * @param WirecardSeamlessPayPalPaymentMethodFactoryInterface $factory
     */
    public function __construct(
        WirecardSeamlessPayPalConfigProviderInterface $configProvider,
        WirecardSeamlessPayPalPaymentMethodFactoryInterface $factory
    ) {
        parent::__construct();
        $this->configProvider = $configProvider;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    protected function collectMethods()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addPayPalMethod($config);
        }
    }

    /**
     * @param WirecardSeamlessPayPalConfigInterface $config
     */
    protected function addPayPalMethod(WirecardSeamlessPayPalConfigInterface $config)
    {
        $this->addMethod(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
